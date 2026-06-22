<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    private array $aiResponses = [
        'default' => [
            "Berdasarkan materi yang telah Anda upload, saya dapat menjelaskan konsep ini secara lebih mendalam. Konsep utama dalam materi ini berkaitan erat dengan prinsip-prinsip dasar yang menjadi fondasi pemahaman yang lebih lanjut.",
            "Pertanyaan yang sangat bagus! Berdasarkan teks yang ada dalam materi Anda, hal ini berkaitan dengan topik yang dibahas di bagian utama. Intinya adalah bagaimana konsep-konsep tersebut saling berinteraksi dalam sistem yang lebih besar.",
            "Dari materi yang saya analisis, perbedaan utamanya terletak pada pendekatan dan implementasi masing-masing konsep. Yang pertama berfokus pada aspek teoritis, sementara yang kedua lebih menekankan aplikasi praktis.",
            "Berdasarkan konteks materi Anda, ada beberapa poin penting yang perlu dipahami. **Pertama**, konsep ini memiliki landasan teoritis yang kuat. **Kedua**, implementasinya dapat dilihat dalam berbagai contoh nyata yang relevan dengan bidang studi Anda.",
            "Analisis saya terhadap materi menunjukkan bahwa topik ini sangat penting untuk dipahami secara menyeluruh. Kunci pemahaman terletak pada hubungan antar konsep yang membentuk kerangka berpikir yang kohesif.",
        ],
        'ai' => [
            "Berdasarkan teks di halaman 2 materi tersebut, perbedaan utamanya adalah: AI adalah payung besar (konsep luas) untuk membuat mesin cerdas, sedangkan **Machine Learning** adalah sub-bidang AI yang fokus pada penggunaan algoritma agar mesin bisa belajar dari data sendiri. 📚 **Lihat Sumber** - Halaman 2, paragraf 3",
            "Halo! Saya sudah mempelajari modul tersebut. Apakah ada bagian tertentu yang ingin kamu tanyakan atau butuh penjelasan lebih detail? Saya siap membantu!",
            "Deep Learning menggunakan arsitektur neural network berlapis-lapis (deep neural networks) yang mampu mengekstrak fitur secara hierarkis dari data mentah. Ini membuatnya sangat efektif untuk tugas seperti pengenalan gambar dan pemrosesan bahasa alami.",
        ],
        'ekonomi' => [
            "Dalam konteks Kurva IS-LM, ketika Bank Sentral meningkatkan jumlah uang beredar (Ms), kurva LM bergeser ke **kanan**. Akibatnya, suku bunga keseimbangan **turun** dan output (Y) **meningkat**. Ini adalah mekanisme kebijakan moneter ekspansif.",
            "Kebijakan fiskal kontraktif bertujuan mengurangi tekanan inflasi dengan cara: (1) Mengurangi pengeluaran pemerintah (G↓) dan (2) Meningkatkan tarif pajak (T↑). Keduanya menurunkan permintaan agregat sehingga menekan laju inflasi.",
            "Stagflasi adalah kondisi anomali ekonomi dimana inflasi tinggi dan pengangguran tinggi terjadi bersamaan. Ini bertentangan dengan kurva Phillips tradisional dan sulit diatasi karena kebijakan anti-inflasi cenderung meningkatkan pengangguran.",
        ],
    ];

    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => ['required', 'string', 'max:1000'],
            'material_id' => ['nullable', 'exists:materials,id'],
        ]);

        $userId = Auth::id();
        $materialId = $request->input('material_id');
        $userMessage = $request->input('message');

        // Check authorization
        if ($materialId) {
            $material = Material::where('id', $materialId)->where('user_id', $userId)->first();
            if (!$material) {
                return response()->json(['error' => 'Material not found'], 404);
            }
        }

        // Save user message
        ChatMessage::create([
            'user_id' => $userId,
            'material_id' => $materialId,
            'role' => 'user',
            'content' => $userMessage,
        ]);

        // Generate AI response
        $aiData = $this->generateAIResponse($userMessage, $materialId);

        // Save AI response
        $assistantMessage = ChatMessage::create([
            'user_id' => $userId,
            'material_id' => $materialId,
            'role' => 'assistant',
            'content' => $aiData['content'],
        ]);

        return response()->json([
            'success' => true,
            'message' => [
                'id' => $assistantMessage->id,
                'role' => 'assistant',
                'content' => $aiData['content'],
                'has_source' => $aiData['has_source'],
                'source_text' => $aiData['source_text'],
                'created_at' => $assistantMessage->created_at->diffForHumans(),
            ],
        ]);
    }

    private function generateAIResponse(string $message, ?int $materialId): array
    {
        $messageLower = strtolower($message);
        
        // Define a comprehensive pool of responses with sources
        $responses = [
            'machine learning' => [
                'content' => "Berdasarkan teks di materi Anda, **Machine Learning** adalah sub-bidang AI yang fokus pada penggunaan algoritma agar mesin bisa belajar dari data sendiri, berbeda dengan AI klasik yang diprogram secara eksplisit.",
                'has_source' => true,
                'source_text' => "Halaman 2, Paragraf 3:\nMachine Learning adalah aplikasi spesifik dari AI yang memberikan sistem kemampuan untuk belajar secara otomatis dan meningkatkan diri dari pengalaman tanpa diprogram secara eksplisit. Fokus utamanya adalah pada pengembangan program komputer yang dapat mengakses data."
            ],
            'deep learning' => [
                'content' => "**Deep Learning** menggunakan arsitektur neural network berlapis-lapis (deep neural networks) yang mampu mengekstrak fitur secara hierarkis dari data mentah. Ini sangat cocok untuk pengenalan gambar.",
                'has_source' => true,
                'source_text' => "Halaman 4, Paragraf 1:\nDeep Learning adalah subset dari Machine Learning yang menggunakan Artificial Neural Networks berlapis banyak untuk menyelesaikan masalah yang lebih kompleks, seperti Computer Vision dan Natural Language Processing."
            ],
            'inflasi' => [
                'content' => "Untuk mengatasi inflasi, kebijakan fiskal kontraktif sering digunakan. Ini melibatkan pengurangan belanja negara atau peningkatan pajak untuk menekan daya beli masyarakat.",
                'has_source' => true,
                'source_text' => "Halaman 5, Modul Ekonomi Makro:\nKebijakan fiskal kontraktif adalah alat utama yang digunakan pemerintah saat ekonomi mengalami overheating dan tingkat inflasi terlalu tinggi dengan cara mengurangi belanja pemerintah (G) dan meningkatkan tingkat pajak (T)."
            ],
            'fiskal' => [
                'content' => "Kebijakan fiskal berkaitan erat dengan bagaimana pemerintah mengatur penerimaan dan pengeluarannya untuk mempengaruhi jalannya perekonomian secara makro.",
                'has_source' => true,
                'source_text' => "Halaman 3, Modul Ekonomi Makro:\nKebijakan fiskal merujuk pada penggunaan pengeluaran pemerintah dan perpajakan untuk mempengaruhi kondisi ekonomi, terutama kondisi makroekonomi, termasuk permintaan agregat barang dan jasa, kesempatan kerja, dan inflasi."
            ],
            'moneter' => [
                'content' => "Kebijakan moneter diatur oleh Bank Sentral (seperti Bank Indonesia) yang berfokus pada pengendalian jumlah uang beredar dan suku bunga.",
                'has_source' => true,
                'source_text' => "Halaman 7, Modul Ekonomi Makro:\nKebijakan Moneter adalah proses di mana otoritas moneter suatu negara mengendalikan pasokan uang, sering kali dengan menargetkan tingkat suku bunga untuk tujuan mempromosikan pertumbuhan dan stabilitas ekonomi."
            ],
            'tujuan' => [
                'content' => "Tujuan utama dari pembelajaran materi ini adalah untuk memberikan fondasi konseptual yang kuat sebelum Anda melangkah ke studi kasus yang lebih kompleks.",
                'has_source' => true,
                'source_text' => "Halaman 1, Pendahuluan:\nTujuan instruksional umum dari modul ini adalah agar mahasiswa dapat memahami dan menjelaskan kembali landasan teori secara komprehensif sebagai bekal penyelesaian masalah di lapangan."
            ],
            'contoh' => [
                'content' => "Berdasarkan materi, salah satu contoh penerapannya yang paling umum dapat ditemukan di sektor industri, di mana otomatisasi telah meningkatkan efisiensi hingga 40%.",
                'has_source' => true,
                'source_text' => "Halaman 12, Studi Kasus:\nSebagai contoh implementasi, adopsi sistem ini di sektor manufaktur terbukti berhasil mereduksi *human error* dan meningkatkan output harian sebesar 40%."
            ],
            'kesimpulan' => [
                'content' => "Kesimpulannya, konsep-konsep ini saling terkait dan membentuk sebuah ekosistem. Pemahaman parsial tidak akan cukup untuk menguasai keseluruhan materi.",
                'has_source' => true,
                'source_text' => "Halaman 20, Penutup:\nKesimpulan yang dapat ditarik adalah integrasi antar sistem merupakan kunci kesuksesan. Tidak ada satu komponen pun yang dapat beroperasi secara optimal dalam isolasi."
            ],
        ];

        // Default random fallback responses
        $fallbacks = [
            [
                'content' => "Topik yang Anda tanyakan sangat menarik. Berdasarkan analisis saya terhadap dokumen Anda, pendekatan yang digunakan bersifat multi-disiplin.",
                'has_source' => true,
                'source_text' => "Dari rangkuman dokumen:\nPendekatan multi-disiplin diperlukan karena kompleksitas masalah tidak dapat lagi diselesaikan melalui lensa tunggal."
            ],
            [
                'content' => "Menurut materi yang diunggah, hal ini berkaitan dengan metodologi dasar yang dibahas pada bab awal.",
                'has_source' => true,
                'source_text' => "Bab 1, Paragraf 2:\nMetodologi dasar ini bertindak sebagai kerangka kerja (framework) utama sebelum mengembangkan asumsi lebih lanjut."
            ],
            [
                'content' => "Pertanyaan bagus! Jika kita merujuk pada PDF Anda, penjelasan mengenai hal tersebut menekankan pentingnya evaluasi berkala.",
                'has_source' => true,
                'source_text' => "Halaman 8, Evaluasi:\nProses evaluasi berkala (continuous assessment) harus dilakukan setiap akhir fase untuk memastikan kualitas tetap terjaga."
            ]
        ];

        // Search for keywords in the user's message
        foreach ($responses as $keyword => $data) {
            if (str_contains($messageLower, $keyword)) {
                return $data;
            }
        }

        // If no keyword matches, return a random fallback response
        return $fallbacks[array_rand($fallbacks)];
    }
}
