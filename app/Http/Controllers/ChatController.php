<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ChatController extends Controller
{
    public function sendMessage(Request $request)
    {
        $request->validate([
            'message'     => ['required', 'string', 'max:1000'],
            'material_id' => ['nullable', 'exists:materials,id'],
        ]);

        $userId      = Auth::id();
        $materialId  = $request->input('material_id');
        $userMessage = $request->input('message');

        $material = null;
        if ($materialId) {
            $material = Material::where('id', $materialId)
                ->where('user_id', $userId)
                ->first();
            if (!$material) {
                return response()->json(['error' => 'Material not found'], 404);
            }
        }

        // Simpan pesan user
        ChatMessage::create([
            'user_id'     => $userId,
            'material_id' => $materialId,
            'role'        => 'user',
            'content'     => $userMessage,
        ]);

        // Generate respons AI menggunakan Gemini
        $aiData = $this->generateGeminiResponse($userMessage, $material);

        // Simpan respons AI
        $assistantMessage = ChatMessage::create([
            'user_id'     => $userId,
            'material_id' => $materialId,
            'role'        => 'assistant',
            'content'     => $aiData['content'],
        ]);

        return response()->json([
            'success' => true,
            'message' => [
                'id'         => $assistantMessage->id,
                'role'       => 'assistant',
                'content'    => $aiData['content'],
                'has_source' => $aiData['has_source'],
                'source_text'=> $aiData['source_text'],
                'created_at' => $assistantMessage->created_at->diffForHumans(),
            ],
        ]);
    }

    private function generateGeminiResponse(string $question, ?Material $material): array
    {
        $apiKey = env('GEMINI_API_KEY');
        
        if (!$apiKey) {
            return [
                'content'     => 'Maaf, API Key Gemini belum dikonfigurasi di sistem.',
                'has_source'  => false,
                'source_text' => '',
            ];
        }

        if (!$material || empty(trim($material->content_text ?? ''))) {
            return [
                'content'     => 'Silakan upload materi terlebih dahulu agar saya bisa menjawab berdasarkan isi dokumen PDF Anda.',
                'has_source'  => false,
                'source_text' => '',
            ];
        }

        // Siapkan prompt instruksi untuk Gemini
        $systemPrompt = "Anda adalah AI Assistant bernama 'BSI Note Book'. Anda bertugas membantu mahasiswa menjawab pertanyaan BERDASARKAN dokumen materi kuliah yang diberikan.
        
PENTING:
1. Jawab HANYA menggunakan informasi dari dokumen di bawah ini.
2. Jika jawabannya tidak ada di dokumen, katakan 'Maaf, saya tidak menemukan informasi tersebut di dalam dokumen materi.'
3. Jawab dengan format Markdown yang rapi dan mudah dibaca (gunakan bullet points, bold, dll).
4. Di bagian akhir, Anda WAJIB memberikan satu kalimat yang merupakan kutipan asli (exact quote) dari dokumen yang mendukung jawaban Anda, diawali dengan tag [KUTIPAN].

DOKUMEN MATERI:
" . $material->content_text;

        // Panggil Gemini API
        $models = explode(',', env('GEMINI_MODELS', 'gemini-1.5-flash,gemini-1.5-pro,gemini-1.0-pro'));
        $lastError = '';

        foreach ($models as $model) {
            $model = trim($model);
            if (empty($model)) continue;

            try {
                $response = Http::timeout(30)->withHeaders([
                    'Content-Type' => 'application/json',
                    'X-goog-api-key' => $apiKey,
                ])->post("https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent", [
                    'contents' => [
                        [
                            'role' => 'user',
                            'parts' => [
                                ['text' => $systemPrompt . "\n\nPERTANYAAN USER:\n" . $question]
                            ]
                        ]
                    ],
                    'generationConfig' => [
                        'temperature' => 0.2, // Rendah agar lebih faktual sesuai PDF
                    ]
                ]);

                if ($response->successful()) {
                    $result = $response->json();
                    $aiText = $result['candidates'][0]['content']['parts'][0]['text'] ?? '';
                    
                    // Pisahkan jawaban dan kutipan
                    $parts = explode('[KUTIPAN]', $aiText);
                    $mainAnswer = trim($parts[0]);
                    $quote = isset($parts[1]) ? trim($parts[1]) : '';

                    if (empty($quote)) {
                        return [
                            'content'     => $mainAnswer,
                            'has_source'  => false,
                            'source_text' => '',
                        ];
                    }

                    // Coba cari lokasi kutipan di PDF
                    $location = 'Dari dokumen PDF';
                    if (preg_match('/\[Halaman (\d+)\]/', $quote, $match)) {
                        $location = "Halaman " . $match[1];
                        $quote = preg_replace('/\[Halaman \d+\]\s*/', '', $quote);
                    } else {
                        // Cari di teks asli untuk menemukan halamannya
                        preg_match_all('/\[Halaman (\d+)\]\n(.*?)(?=\[Halaman \d+\]|$)/s', $material->content_text, $pages);
                        for ($i = 0; $i < count($pages[1]); $i++) {
                            // Cari sebagian kata dari kutipan
                            $snippet = substr($quote, 0, 50);
                            if (str_contains(strtolower($pages[2][$i]), strtolower($snippet))) {
                                $location = "Halaman " . $pages[1][$i];
                                break;
                            }
                        }
                    }

                    return [
                        'content'     => $mainAnswer,
                        'has_source'  => true,
                        'source_text' => $location . "\n" . trim(str_replace(['"', '*'], '', $quote)),
                    ];
                }

                $lastStatus = $response->status();
                $lastError = 'Error: ' . $lastStatus;
                
                // Jika error bukan karena rate limit (429) atau server error (500/503), hentikan pencarian.
                if (!in_array($lastStatus, [429, 500, 503])) {
                    break;
                }

            } catch (\Exception $e) {
                $lastError = 'Koneksi gagal: ' . $e->getMessage();
                continue;
            }
        }

        return [
            'content'     => 'Terjadi kesalahan saat menghubungi server AI. ' . $lastError . '. Semua model yang tersedia telah dicoba.',
            'has_source'  => false,
            'source_text' => '',
        ];
    }
}
