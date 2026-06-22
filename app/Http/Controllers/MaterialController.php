<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MaterialController extends Controller
{
    // Pre-defined AI summaries for demo
    private array $sampleSummaries = [
        "## Ringkasan Materi\n\nMateri ini membahas konsep-konsep utama yang perlu dipahami secara mendalam. Berikut adalah poin-poin kunci:\n\n**Konsep Utama**\n\nPemahaman dasar diperlukan sebagai fondasi sebelum memahami topik yang lebih kompleks. Setiap konsep saling berkaitan dan membentuk sistem yang kohesif.\n\n**Tiga Pilar Utama**\n\n- **Pilar Pertama:** Fondasi teoritis yang menjelaskan bagaimana sistem bekerja secara fundamental\n- **Pilar Kedua:** Implementasi praktis dari teori ke dalam aplikasi nyata\n- **Pilar Ketiga:** Evaluasi dan pengembangan berkelanjutan berdasarkan feedback\n\n**Aplikasi dalam Kehidupan Nyata**\n\nPengetahuan ini dapat diterapkan dalam berbagai bidang, mulai dari industri teknologi hingga kehidupan sehari-hari. Pemahaman mendalam akan membuka peluang untuk inovasi yang lebih besar.",
        "## Ringkasan: Pengantar Artificial Intelligence\n\nArtificial Intelligence (AI) atau Kecerdasan Buatan adalah simulasi proses kecerdasan manusia oleh mesin, terutama sistem komputer. Proses-proses ini mencakup pembelajaran (pemrolehan informasi dan aturan untuk menggunakan informasi), penalaran (menggunakan aturan untuk mencapai perkiraan atau kesimpulan pasti), dan koreksi diri.\n\n**Tiga Pilar Utama AI**\n\n- **Machine Learning:** Kemampuan sistem untuk belajar secara otomatis dari data tanpa diprogram secara eksplisit\n- **Deep Learning:** Sub-bidang ML yang menggunakan neural networks berlapis untuk memproses data kompleks\n- **Natural Language Processing:** Kemampuan komputer untuk memahami dan menghasilkan bahasa manusia\n\n**Aplikasi AI Modern**\n\nAI telah merevolusi berbagai industri: dari healthcare (diagnosis medis), finance (deteksi fraud), hingga education (personalized learning).",
        "## Ringkasan: Dasar Ekonomi Makro\n\nEkonomi Makro adalah cabang ilmu ekonomi yang mempelajari perilaku dan kinerja ekonomi secara keseluruhan. Fokus utamanya adalah variabel agregat seperti output nasional, inflasi, pengangguran, dan kebijakan ekonomi.\n\n**Kebijakan Fiskal & Moneter**\n\nKebijakan fiskal meliputi pengeluaran pemerintah dan perpajakan untuk mempengaruhi perekonomian. Kebijakan moneter melibatkan pengelolaan jumlah uang beredar dan suku bunga oleh bank sentral.\n\n**Kurva IS-LM**\n\nModel IS-LM menggambarkan keseimbangan simultan di pasar barang (kurva IS) dan pasar uang (kurva LM). Kenaikan jumlah uang beredar menggeser kurva LM ke kanan, menurunkan suku bunga dan meningkatkan output.",
    ];

    public function store(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:pdf,doc,docx,txt,pptx', 'max:20480'],
            'title' => ['nullable', 'string', 'max:255'],
        ]);

        $file = $request->file('file');
        $originalName = $file->getClientOriginalName();
        $title = $request->input('title', pathinfo($originalName, PATHINFO_FILENAME));
        $filePath = $file->store('materials', 'public');
        $fileSize = $file->getSize();

        $colors = ['#4CAF50', '#2196F3', '#9C27B0', '#FF5722', '#009688', '#FF9800'];
        $color = $colors[array_rand($colors)];

        $summaryIndex = rand(0, count($this->sampleSummaries) - 1);

        $material = Material::create([
            'user_id' => Auth::id(),
            'title' => $title,
            'file_path' => $filePath,
            'file_type' => $file->getClientOriginalExtension(),
            'file_size' => $fileSize,
            'status' => 'indexed',
            'summary' => $this->sampleSummaries[$summaryIndex],
            'content_text' => 'Konten materi telah berhasil diindeks oleh sistem AI BSI Note Book.',
            'cover_color' => $color,
        ]);

        $redirectTo = $request->input('redirect_to', 'dashboard');
        
        if ($redirectTo === 'library') {
            return redirect()->route('library')
                ->with('success', 'Materi "' . $title . '" berhasil diupload dan diindeks!');
        }

        return redirect()->route('dashboard', ['material' => $material->id])
            ->with('success', 'Materi "' . $title . '" berhasil diupload dan diindeks!');
    }

    public function destroy(Material $material)
    {
        if ($material->user_id !== Auth::id()) {
            abort(403);
        }

        if ($material->file_path) {
            Storage::disk('public')->delete($material->file_path);
        }

        $material->delete();

        return redirect()->route('dashboard')->with('success', 'Materi berhasil dihapus.');
    }
}
