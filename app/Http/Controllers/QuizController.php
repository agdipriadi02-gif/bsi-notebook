<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    public function index()
    {
        $materials = Material::where('user_id', Auth::id())->latest()->paginate(12);
        return view('quiz.index', compact('materials'));
    }

    // Sample questions pool
    private function generateSampleQuestions(string $topic, int $count = 10): array
    {
        $questionPools = [
            'ai' => [
                [
                    'question' => 'Apa perbedaan paling mendasar antara AI dan Machine Learning?',
                    'context' => 'Berdasarkan materi yang telah dipelajari tentang konsep dasar AI.',
                    'options' => [
                        'A' => 'AI adalah sub-bidang dari Machine Learning',
                        'B' => 'Machine Learning adalah sub-bidang dari AI yang berfokus pada pembelajaran dari data',
                        'C' => 'Keduanya adalah konsep yang sama persis',
                        'D' => 'Machine Learning tidak membutuhkan data untuk belajar',
                    ],
                    'correct_answer' => 'B',
                    'explanation' => 'Machine Learning adalah sub-bidang AI yang memungkinkan sistem belajar dari data secara otomatis tanpa pemrograman eksplisit.',
                ],
                [
                    'question' => 'Manakah yang merupakan contoh penerapan Natural Language Processing?',
                    'context' => 'NLP adalah salah satu cabang utama AI.',
                    'options' => [
                        'A' => 'Pengolahan gambar digital',
                        'B' => 'Chatbot dan asisten virtual seperti Siri',
                        'C' => 'Pengenalan wajah pada kamera',
                        'D' => 'Permainan catur komputer',
                    ],
                    'correct_answer' => 'B',
                    'explanation' => 'NLP memungkinkan komputer memahami dan menghasilkan bahasa manusia, digunakan dalam chatbot dan asisten virtual.',
                ],
                [
                    'question' => 'Deep Learning menggunakan struktur apa dalam mempelajari pola data?',
                    'options' => [
                        'A' => 'Database relasional',
                        'B' => 'Algoritma pencarian sederhana',
                        'C' => 'Neural networks berlapis',
                        'D' => 'Spreadsheet data',
                    ],
                    'correct_answer' => 'C',
                    'explanation' => 'Deep Learning menggunakan neural networks berlapis (deep neural networks) untuk memproses data kompleks.',
                ],
            ],
            'ekonomi' => [
                [
                    'question' => 'Manakah dari berikut ini yang merupakan instrumen utama dari kebijakan fiskal yang digunakan pemerintah untuk mengendalikan inflasi?',
                    'context' => 'Pilihlah jawaban yang paling tepat berdasarkan materi kuliah Minggu ke-3.',
                    'options' => [
                        'A' => 'Penurunan suku bunga acuan bank sentral',
                        'B' => 'Peningkatan pengeluaran pemerintah dan penurunan pajak',
                        'C' => 'Pengurangan pengeluaran pemerintah dan peningkatan tarif pajak',
                        'D' => 'Operasi pasar terbuka melalui pembelian obligasi negara',
                    ],
                    'correct_answer' => 'C',
                    'explanation' => 'Untuk mengendalikan inflasi, pemerintah menggunakan kebijakan fiskal kontraktif: mengurangi pengeluaran dan meningkatkan pajak untuk menyerap uang dari peredaran.',
                ],
                [
                    'question' => 'Dalam konteks kurva IS-LM, apa yang terjadi jika terjadi kenaikan jumlah uang beredar (Money Supply)?',
                    'options' => [
                        'A' => 'Kurva LM bergeser ke kanan, menurunkan suku bunga dan meningkatkan output',
                        'B' => 'Kurva IS bergeser ke kiri, menurunkan suku bunga dan output',
                        'C' => 'Kurva LM bergeser ke kiri, meningkatkan suku bunga',
                        'D' => 'Tidak ada perubahan pada kurva IS-LM',
                    ],
                    'correct_answer' => 'A',
                    'explanation' => 'Kenaikan money supply menggeser kurva LM ke kanan, menyebabkan penurunan suku bunga dan kenaikan output (pendapatan nasional).',
                ],
                [
                    'question' => 'Apa yang dimaksud dengan "Stagflasi" dalam ilmu ekonomi makro?',
                    'options' => [
                        'A' => 'Pertumbuhan ekonomi yang sangat pesat',
                        'B' => 'Kondisi inflasi tinggi bersamaan dengan pengangguran tinggi',
                        'C' => 'Penurunan harga barang secara terus-menerus',
                        'D' => 'Keseimbangan sempurna antara inflasi dan pengangguran',
                    ],
                    'correct_answer' => 'B',
                    'explanation' => 'Stagflasi adalah kondisi ekonomi yang ditandai oleh inflasi tinggi, pertumbuhan ekonomi lambat, dan tingkat pengangguran yang tinggi secara bersamaan.',
                ],
            ],
            'default' => [
                [
                    'question' => 'Manakah pernyataan yang paling tepat menggambarkan konsep utama dari materi ini?',
                    'context' => 'Berdasarkan ringkasan materi yang telah diproses oleh AI.',
                    'options' => [
                        'A' => 'Konsep ini hanya berlaku dalam konteks teoritis semata',
                        'B' => 'Pemahaman mendalam diperlukan sebagai fondasi untuk konsep yang lebih kompleks',
                        'C' => 'Materi ini tidak berkaitan dengan aplikasi praktis',
                        'D' => 'Konsep ini sudah tidak relevan di era modern',
                    ],
                    'correct_answer' => 'B',
                    'explanation' => 'Pemahaman yang kuat atas dasar-dasar teoritis sangat penting sebagai fondasi untuk mempelajari konsep yang lebih lanjut.',
                ],
                [
                    'question' => 'Apa keuntungan utama dari penerapan pendekatan yang sistematis dalam mempelajari materi ini?',
                    'options' => [
                        'A' => 'Mempercepat proses hafalan tanpa perlu memahami',
                        'B' => 'Membantu membangun pemahaman yang komprehensif dan terstruktur',
                        'C' => 'Mengurangi waktu belajar secara signifikan tanpa efek samping',
                        'D' => 'Tidak ada keuntungan yang signifikan',
                    ],
                    'correct_answer' => 'B',
                    'explanation' => 'Pendekatan sistematis membantu membangun pemahaman yang lebih dalam dan terstruktur, memudahkan retensi jangka panjang.',
                ],
                [
                    'question' => 'Dalam konteks materi ini, bagaimana hubungan antara teori dan praktik?',
                    'options' => [
                        'A' => 'Teori dan praktik adalah dua hal yang sepenuhnya terpisah',
                        'B' => 'Hanya praktik yang penting, teori tidak diperlukan',
                        'C' => 'Teori memberikan fondasi untuk implementasi praktis yang efektif',
                        'D' => 'Praktik selalu lebih penting dari teori dalam semua situasi',
                    ],
                    'correct_answer' => 'C',
                    'explanation' => 'Teori dan praktik saling melengkapi: teori memberikan kerangka pemahaman, sementara praktik mengaplikasikan pemahaman tersebut.',
                ],
                [
                    'question' => 'Manakah yang merupakan langkah pertama yang tepat dalam mempelajari topik baru?',
                    'options' => [
                        'A' => 'Langsung mengerjakan soal latihan tanpa membaca materi',
                        'B' => 'Memahami konsep dasar dan terminologi kunci terlebih dahulu',
                        'C' => 'Menghafal semua rumus dan definisi',
                        'D' => 'Mencari jawaban di internet tanpa memahami konteks',
                    ],
                    'correct_answer' => 'B',
                    'explanation' => 'Memahami konsep dasar dan terminologi adalah langkah fundamental yang memudahkan pembelajaran konsep yang lebih kompleks.',
                ],
                [
                    'question' => 'Bagaimana cara terbaik untuk mengevaluasi pemahaman Anda terhadap suatu materi?',
                    'options' => [
                        'A' => 'Hanya dengan membaca ulang catatan',
                        'B' => 'Dengan menguji diri sendiri melalui kuis dan soal latihan',
                        'C' => 'Dengan meminta orang lain menjelaskan materi tersebut',
                        'D' => 'Dengan menghitung berapa lama Anda belajar',
                    ],
                    'correct_answer' => 'B',
                    'explanation' => 'Self-testing melalui kuis adalah metode yang terbukti efektif untuk mengevaluasi dan memperkuat pemahaman (retrieval practice).',
                ],
                [
                    'question' => 'Apa yang dimaksud dengan pemahaman konseptual yang mendalam?',
                    'options' => [
                        'A' => 'Kemampuan menghafal fakta dan definisi secara verbatim',
                        'B' => 'Kemampuan menjelaskan, menerapkan, dan menghubungkan konsep dalam berbagai konteks',
                        'C' => 'Kecepatan membaca materi dalam waktu singkat',
                        'D' => 'Kemampuan mengingat semua detail tanpa pengecualian',
                    ],
                    'correct_answer' => 'B',
                    'explanation' => 'Pemahaman konseptual yang mendalam ditandai dengan kemampuan menjelaskan dengan kata-kata sendiri, menerapkan dalam konteks baru, dan menghubungkan dengan konsep lain.',
                ],
                [
                    'question' => 'Mengapa penting untuk meninjau materi secara berkala setelah mempelajarinya?',
                    'options' => [
                        'A' => 'Untuk mengisi waktu luang',
                        'B' => 'Untuk melawan efek forgetting curve dan memperkuat ingatan jangka panjang',
                        'C' => 'Karena materi selalu berubah setiap harinya',
                        'D' => 'Untuk memenuhi persyaratan akademis saja',
                    ],
                    'correct_answer' => 'B',
                    'explanation' => 'Spaced repetition (pengulangan berkala) adalah teknik yang terbukti efektif untuk melawan forgetting curve dan memperkuat ingatan jangka panjang.',
                ],
                [
                    'question' => 'Manakah pendekatan belajar yang paling efektif untuk pemahaman jangka panjang?',
                    'options' => [
                        'A' => 'Belajar dalam satu sesi panjang (cramming) sebelum ujian',
                        'B' => 'Belajar secara terdistribusi dengan sesi pendek namun sering',
                        'C' => 'Hanya membaca tanpa membuat catatan',
                        'D' => 'Mengandalkan memori fotografis',
                    ],
                    'correct_answer' => 'B',
                    'explanation' => 'Distributed practice (belajar terdistribusi) jauh lebih efektif daripada cramming untuk retensi jangka panjang.',
                ],
                [
                    'question' => 'Apa peran peta konsep dalam proses belajar?',
                    'options' => [
                        'A' => 'Hanya sebagai dekorasi visual yang menarik',
                        'B' => 'Membantu memvisualisasikan hubungan antar konsep dan struktur pengetahuan',
                        'C' => 'Menggantikan kebutuhan untuk membaca materi utama',
                        'D' => 'Tidak memiliki nilai pedagogis yang signifikan',
                    ],
                    'correct_answer' => 'B',
                    'explanation' => 'Peta konsep membantu mengorganisir dan memvisualisasikan hubungan antara konsep-konsep, memperkuat pemahaman struktural.',
                ],
                [
                    'question' => 'Bagaimana teknologi AI dapat membantu proses pembelajaran mahasiswa?',
                    'options' => [
                        'A' => 'Menggantikan sepenuhnya peran dosen dan buku teks',
                        'B' => 'Memberikan pengalaman belajar yang dipersonalisasi dan umpan balik instan',
                        'C' => 'Hanya berguna untuk menggerjakan tugas secara otomatis',
                        'D' => 'Membuat mahasiswa menjadi malas belajar',
                    ],
                    'correct_answer' => 'B',
                    'explanation' => 'AI dapat memberikan pengalaman belajar yang dipersonalisasi, umpan balik instan, dan adaptasi materi sesuai kebutuhan individu mahasiswa.',
                ],
            ],
        ];

        // Determine which pool to use based on topic keywords
        $pool = $questionPools['default'];
        $topicLower = strtolower($topic);
        if (str_contains($topicLower, 'ai') || str_contains($topicLower, 'artificial') || str_contains($topicLower, 'kecerdasan')) {
            $pool = array_merge($questionPools['ai'], $questionPools['default']);
        } elseif (str_contains($topicLower, 'ekonomi') || str_contains($topicLower, 'makro') || str_contains($topicLower, 'fiskal')) {
            $pool = array_merge($questionPools['ekonomi'], $questionPools['default']);
        }

        shuffle($pool);
        return array_slice($pool, 0, min($count, count($pool)));
    }

    public function generate(Request $request, Material $material)
    {
        if ($material->user_id !== Auth::id()) {
            abort(403);
        }

        $topic = $request->input('topic', $material->title);
        $questionCount = min((int) $request->input('count', 10), 15);

        $quiz = Quiz::create([
            'material_id' => $material->id,
            'title' => 'Kuis: ' . $material->title,
            'topic' => $topic,
            'total_questions' => $questionCount,
            'time_limit_minutes' => 15,
        ]);

        // Coba generate soal dari AI
        $questions = $this->generateQuestionsFromAI($material, $questionCount);
        
        // Fallback jika AI gagal atau materi kosong
        if (empty($questions)) {
            $questions = $this->generateSampleQuestions($topic, $questionCount);
        }

        foreach ($questions as $index => $q) {
            QuizQuestion::create([
                'quiz_id' => $quiz->id,
                'order' => $index + 1,
                'question' => $q['question'],
                'context' => $q['context'] ?? null,
                'options' => $q['options'],
                'correct_answer' => $q['correct_answer'],
                'explanation' => $q['explanation'],
            ]);
        }

        $attempt = QuizAttempt::create([
            'user_id' => Auth::id(),
            'quiz_id' => $quiz->id,
            'started_at' => now(),
        ]);

        return redirect()->route('quiz.show', ['quiz' => $quiz->id, 'attempt' => $attempt->id]);
    }

    private function generateQuestionsFromAI(Material $material, int $count): array
    {
        $apiKey = env('GEMINI_API_KEY');
        if (!$apiKey || empty(trim($material->content_text ?? ''))) {
            return [];
        }

        $prompt = "Tugas Anda adalah membuat {$count} soal pilihan ganda berdasarkan teks dokumen di bawah ini.
Setiap soal harus memiliki 4 pilihan jawaban (A, B, C, D) dan satu penjelasan mengapa jawaban tersebut benar berdasarkan teks.

PENTING: Anda WAJIB mengembalikan respon HANYA dalam format JSON ARRAY murni yang valid seperti struktur di bawah ini. JANGAN tambahkan teks pengantar apapun (seperti ```json).

Struktur JSON yang diharapkan:
[
  {
    \"question\": \"Pertanyaan...\",
    \"context\": \"Konteks tambahan jika perlu (bisa kosong)...\",
    \"options\": {
      \"A\": \"Pilihan A\",
      \"B\": \"Pilihan B\",
      \"C\": \"Pilihan C\",
      \"D\": \"Pilihan D\"
    },
    \"correct_answer\": \"A\",
    \"explanation\": \"Penjelasan mengapa A benar...\"
  }
]

TEKS DOKUMEN:
" . \Illuminate\Support\Str::limit($material->content_text, 50000); // Batasi agar tidak terlalu berat

        $models = explode(',', env('GEMINI_MODELS', 'gemini-3.5-flash,gemini-2.5-flash,gemini-2.5-pro'));

        foreach ($models as $model) {
            $model = trim($model);
            if (empty($model)) continue;

            try {
                $response = \Illuminate\Support\Facades\Http::timeout(60)->withHeaders([
                    'Content-Type' => 'application/json',
                    'X-goog-api-key' => $apiKey,
                ])->post("https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent", [
                    'contents' => [
                        [
                            'role' => 'user',
                            'parts' => [
                                ['text' => $prompt]
                            ]
                        ]
                    ],
                    'generationConfig' => [
                        'temperature' => 0.4,
                    ]
                ]);

                if ($response->successful()) {
                    $result = $response->json();
                    $aiText = $result['candidates'][0]['content']['parts'][0]['text'] ?? '';
                    
                    // Bersihkan respon AI dari tag markdown ```json jika terbawa
                    $aiText = preg_replace('/```json\s*/', '', $aiText);
                    $aiText = preg_replace('/```\s*/', '', $aiText);
                    $aiText = trim($aiText);

                    $decoded = json_decode($aiText, true);

                    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                        return $decoded;
                    }
                }
                
                $status = $response->status();
                if (!in_array($status, [429, 500, 503])) {
                    break;
                }
            } catch (\Exception $e) {
                // Lanjut ke model berikutnya jika gagal
                continue;
            }
        }

        return [];
    }

    public function show(Quiz $quiz, Request $request)
    {
        $material = $quiz->material;
        if ($material->user_id !== Auth::id()) {
            abort(403);
        }

        $attemptId = $request->query('attempt');
        $attempt = null;
        if ($attemptId) {
            $attempt = QuizAttempt::where('id', $attemptId)->where('user_id', Auth::id())->first();
        }

        $questions = $quiz->questions;

        return view('quiz.show', compact('quiz', 'questions', 'attempt', 'material'));
    }

    public function submit(Request $request, Quiz $quiz)
    {
        $material = $quiz->material;
        if ($material->user_id !== Auth::id()) {
            abort(403);
        }

        $answers = $request->input('answers', []);
        $attemptId = $request->input('attempt_id');

        $questions = $quiz->questions;
        $correctCount = 0;

        foreach ($questions as $question) {
            $selected = $answers[$question->id] ?? null;
            if ($selected === $question->correct_answer) {
                $correctCount++;
            }
        }

        $score = $questions->count() > 0 ? round(($correctCount / $questions->count()) * 100) : 0;

        $attempt = null;
        if ($attemptId) {
            $attempt = QuizAttempt::find($attemptId);
        }

        if ($attempt) {
            $attempt->update([
                'answers' => $answers,
                'score' => $score,
                'correct_count' => $correctCount,
                'finished_at' => now(),
            ]);
        } else {
            $attempt = QuizAttempt::create([
                'user_id' => Auth::id(),
                'quiz_id' => $quiz->id,
                'answers' => $answers,
                'score' => $score,
                'correct_count' => $correctCount,
                'started_at' => now(),
                'finished_at' => now(),
            ]);
        }

        return redirect()->route('quiz.result', ['quiz' => $quiz->id, 'attempt' => $attempt->id]);
    }

    public function result(Quiz $quiz, Request $request)
    {
        $material = $quiz->material;
        if ($material->user_id !== Auth::id()) {
            abort(403);
        }

        $attemptId = $request->query('attempt');
        $attempt = QuizAttempt::where('id', $attemptId)->where('user_id', Auth::id())->firstOrFail();
        $questions = $quiz->questions;

        return view('quiz.result', compact('quiz', 'questions', 'attempt', 'material'));
    }
}
