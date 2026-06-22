<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MaterialController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'file'  => ['required', 'file', 'mimes:pdf,doc,docx,txt,pptx', 'max:20480'],
            'title' => ['nullable', 'string', 'max:255'],
        ]);

        $file         = $request->file('file');
        $originalName = $file->getClientOriginalName();
        $title        = $request->input('title', pathinfo($originalName, PATHINFO_FILENAME));
        $filePath     = $file->store('materials', 'public');
        $fileSize     = $file->getSize();
        $extension    = strtolower($file->getClientOriginalExtension());

        $colors = ['#4CAF50', '#2196F3', '#9C27B0', '#FF5722', '#009688', '#FF9800'];
        $color  = $colors[array_rand($colors)];

        // ── Ekstrak teks dari file ────────────────────────────────────────────
        $contentText = '';
        $summary     = '';

        try {
            $absolutePath = Storage::disk('public')->path($filePath);

            if ($extension === 'pdf') {
                $contentText = $this->extractPdfText($absolutePath);
            } elseif ($extension === 'txt') {
                $contentText = file_get_contents($absolutePath);
            } else {
                $contentText = 'Format file ini belum mendukung ekstraksi teks otomatis.';
            }

            // Buat ringkasan dari teks yang diekstrak
            $summary = $this->generateSummary($title, $contentText);

        } catch (\Exception $e) {
            $contentText = '';
            $summary     = "## {$title}\n\nRingkasan belum tersedia. Teks dari file tidak dapat diekstrak secara otomatis.";
        }

        $material = Material::create([
            'user_id'      => Auth::id(),
            'title'        => $title,
            'file_path'    => $filePath,
            'file_type'    => $extension,
            'file_size'    => $fileSize,
            'status'       => 'indexed',
            'summary'      => $summary,
            'content_text' => $contentText,
            'cover_color'  => $color,
        ]);

        $redirectTo = $request->input('redirect_to', 'dashboard');

        if ($redirectTo === 'library') {
            return redirect()->route('library')
                ->with('success', 'Materi "' . $title . '" berhasil diupload dan diindeks!');
        }

        return redirect()->route('dashboard', ['material' => $material->id])
            ->with('success', 'Materi "' . $title . '" berhasil diupload dan diindeks!');
    }

    /**
     * Ekstrak teks dari file PDF menggunakan smalot/pdfparser
     */
    private function extractPdfText(string $path): string
    {
        if (!class_exists('\Smalot\PdfParser\Parser')) {
            return '';
        }

        $parser  = new \Smalot\PdfParser\Parser();
        $pdf     = $parser->parseFile($path);
        $pages   = $pdf->getPages();
        $result  = [];

        foreach ($pages as $i => $page) {
            $pageNum  = $i + 1;
            $text     = trim($page->getText());
            if ($text !== '') {
                $result[] = "[Halaman {$pageNum}]\n{$text}";
            }
        }

        return implode("\n\n", $result);
    }

    /**
     * Buat ringkasan sederhana dari teks yang diekstrak
     */
    private function generateSummary(string $title, string $contentText): string
    {
        if (empty(trim($contentText))) {
            return "## {$title}\n\nRingkasan belum tersedia. File mungkin berupa gambar/scan yang tidak dapat dibaca teksnya.";
        }

        // Ambil ~800 karakter pertama sebagai pratinjau ringkasan
        $preview = Str::limit(strip_tags($contentText), 800, '...');

        return "## Ringkasan: {$title}\n\n"
            . "Materi ini telah berhasil diindeks. Berikut adalah pratinjau isi dokumen:\n\n"
            . "> {$preview}\n\n"
            . "Silakan tanyakan hal apapun tentang materi ini kepada Asisten AI di sebelah kanan.";
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
