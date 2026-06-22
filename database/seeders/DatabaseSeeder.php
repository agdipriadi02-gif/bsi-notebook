<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Material;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Demo User
        $user = User::firstOrCreate(
            ['email' => 'demo@bsinotebook.id'],
            [
                'name' => 'Mahasiswa Demo',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        // Sample materials for demo
        $materials = [
            [
                'title' => 'Modul_1_Pengantar Artificial Intelligence',
                'file_type' => 'pdf',
                'file_size' => '2456000',
                'status' => 'indexed',
                'cover_color' => '#4CAF50',
                'summary' => "## Ringkasan: Pengantar Artificial Intelligence\n\nArtificial Intelligence (AI) atau Kecerdasan Buatan adalah simulasi proses kecerdasan manusia oleh mesin, terutama sistem komputer. Proses-proses ini mencakup pembelajaran (pemrolehan informasi dan aturan untuk menggunakan informasi), penalaran (menggunakan aturan untuk mencapai perkiraan atau kesimpulan pasti), dan koreksi diri.\n\n**Tiga Pilar Utama AI**\n\n- **Machine Learning:** Kemampuan sistem untuk belajar secara otomatis dari data tanpa diprogram secara eksplisit untuk setiap tugas\n- **Deep Learning:** Sub-bidang ML yang menggunakan neural networks berlapis untuk memproses data yang sangat kompleks seperti gambar dan suara\n- **Natural Language Processing:** Kemampuan komputer untuk memahami, menginterpretasikan, dan menghasilkan bahasa manusia\n\n**Aplikasi AI Modern**\n\nAI telah merevolusi berbagai industri:\n- Healthcare: Diagnosis medis berbasis citra\n- Finance: Deteksi fraud secara real-time\n- Education: Sistem belajar yang dipersonalisasi\n- Transportation: Kendaraan otonom",
            ],
            [
                'title' => 'Catatan Kuliah Minggu ke-3',
                'file_type' => 'docx',
                'file_size' => '892000',
                'status' => 'indexed',
                'cover_color' => '#2196F3',
                'summary' => "## Catatan Kuliah – Minggu ke-3\n\nTopik minggu ini membahas tentang penerapan konsep-konsep yang telah dipelajari sebelumnya dalam konteks yang lebih praktis.\n\n**Poin-poin Utama**\n\n- Implementasi algoritma dalam bahasa pemrograman modern\n- Studi kasus industri nyata yang relevan\n- Diskusi kelompok tentang tantangan dan solusi\n\n**Tugas**\n\nMahasiswa diminta untuk membuat analisis singkat tentang penerapan konsep yang dipelajari dalam bidang studi masing-masing.",
            ],
            [
                'title' => 'Artikel: Sejarah Algoritma',
                'file_type' => 'pdf',
                'file_size' => '1234000',
                'status' => 'indexed',
                'cover_color' => '#9C27B0',
                'summary' => "## Sejarah dan Perkembangan Algoritma\n\nAlgoritma adalah serangkaian instruksi yang terdefinisi dengan baik untuk menyelesaikan suatu masalah dalam jumlah langkah yang terbatas.\n\n**Sejarah Singkat**\n\n- 825 M: Al-Khwarizmi memperkenalkan konsep prosedur matematika sistematis\n- 1843: Ada Lovelace menulis algoritma pertama untuk mesin Babbage\n- 1936: Alan Turing mendefinisikan konsep komputabilitas\n- 1950-an: Dimulainya era komputer modern dan algoritma digital\n\n**Jenis Algoritma**\n\n- Sorting (Pengurutan): Bubble Sort, Quick Sort, Merge Sort\n- Searching (Pencarian): Binary Search, Linear Search\n- Graph: Dijkstra, BFS, DFS",
            ],
        ];

        foreach ($materials as $materialData) {
            Material::firstOrCreate(
                ['user_id' => $user->id, 'title' => $materialData['title']],
                array_merge($materialData, ['user_id' => $user->id, 'content_text' => 'Konten telah diindeks oleh BSI AI Engine.'])
            );
        }
    }
}
