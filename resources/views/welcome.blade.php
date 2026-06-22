<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BSI Note Book – Belajar Lebih Cerdas dengan AI</title>
    <meta name="description" content="Asisten AI yang membantu mahasiswa meringkas materi, membuat soal latihan, dan menjawab pertanyaan langsung dari dokumen PDF Anda.">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%235cb85c'><path d='M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5'/></svg>">
</head>
<body class="landing-body">

<nav class="navbar" id="navbar">
    <div class="nav-brand">
        <div class="brand-icon">📒</div>
        <span>BSI <strong>Note Book</strong></span>
    </div>
    <ul class="nav-links">
        <li><a href="{{ route('home') }}" class="active">Beranda</a></li>
        <li><a href="{{ Auth::check() ? route('library') : route('register') }}">Perpustakaan</a></li>
        <li><a href="#tentang">Tentang Kami</a></li>
    </ul>
    <div style="display:flex;align-items:center;gap:12px;">
        @auth
            <button class="header-icon-btn" style="background:rgba(0,0,0,0.04);border-color:rgba(0,0,0,0.08);color:#666;">🔔</button>
        @endauth
        @auth
            <a href="{{ route('dashboard') }}" class="btn-nav-primary">📚 Dashboard</a>
        @else
            <a href="{{ route('register') }}" class="btn-nav-primary">✦ Mulai Belajar</a>
        @endauth
    </div>
</nav>

<!-- Hero Section -->
<section class="hero-section">
    <div class="hero-badge">✦ Didukung oleh AI Terpercaya</div>
    <h1 class="hero-title">
        Belajar Lebih Cerdas dengan<br>
        <span class="highlight">BSI Note Book</span>
    </h1>
    <p class="hero-subtitle">
        Asisten AI yang membantu mahasiswa meringkas materi, membuat soal latihan, dan menjawab pertanyaan langsung dari dokumen PDF Anda.
    </p>
    <div class="hero-actions">
        <a href="{{ route('register') }}" class="btn-primary-hero">
            Mulai Belajar Sekarang →
        </a>
        <a href="{{ route('login') }}" class="btn-secondary-hero">
            ▶ Lihat Demo
        </a>
    </div>

    <!-- App Mockup -->
    <div class="hero-mockup">
        <div class="mockup-window">
            <div class="mockup-titlebar">
                <div class="mockup-dot red"></div>
                <div class="mockup-dot yellow"></div>
                <div class="mockup-dot green"></div>
            </div>
            <div class="mockup-body">
                <div class="mockup-sidebar">
                    <div class="mockup-sidebar-item">📤 Unggah Materi</div>
                    <div class="mockup-sidebar-item active">📄 Modul_1_Peng...</div>
                    <div class="mockup-sidebar-item">📝 Catatan Kuliah...</div>
                    <div class="mockup-sidebar-item">🔗 Artikel: Sejarah Al...</div>
                </div>
                <div class="mockup-content">
                    <div style="display:flex;gap:6px;margin-bottom:12px;flex-wrap:wrap;">
                        <span class="mockup-chip" style="background:#1e2435;color:#fff;">Summary AI</span>
                        <span class="mockup-chip">⏱ Reading Time: 5 min</span>
                    </div>
                    <div class="mockup-content-title">Ringkasan: Pengantar AI</div>
                    <div class="mockup-content-text">Artificial Intelligence (AI) atau Kecerdasan Buatan adalah simulasi proses kecerdasan manusia oleh mesin. Proses-proses ini mencakup pembelajaran, penalaran, dan koreksi diri secara berkelanjutan...</div>
                    <div class="mockup-content-chips">
                        <span class="mockup-chip">🎙 Audio</span>
                        <span class="mockup-chip">📊 Slide Deck</span>
                        <span class="mockup-chip">🗺 Mind Map</span>
                        <span class="mockup-chip">🃏 Flashcards</span>
                    </div>
                </div>
                <div class="mockup-chat">
                    <div class="mockup-chat-header">🤖 Asisten Studi AI</div>
                    <div class="mockup-chat-body">
                        <div class="mockup-bubble ai">Halo! Saya sudah mempelajari modul Pengantar AI. Ada yang ingin ditanyakan?</div>
                        <div class="mockup-bubble user">Apa perbedaan AI dan Machine Learning?</div>
                        <div class="mockup-bubble ai">Berdasarkan teks halaman 2, AI adalah payung besar sedangkan <strong>Machine Learning</strong> adalah sub-bidangnya...</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="pdf-float">
            <div class="pdf-icon">📄</div>
            <div>
                <div style="font-weight:700;font-size:0.8rem;color:#1a1a2e;">PDF Teranalisa</div>
                <div style="font-size:0.7rem;color:#888;">Makalah diproses dengan AI</div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="features-section" id="tentang">
    <div class="section-header">
        <h2 class="section-title">Fitur Utama untuk Kesuksesan Akademik</h2>
        <p class="section-subtitle">Dirancang khusus untuk membantu mahasiswa menguasai materi kuliah dengan cepat dan efektif.</p>
    </div>

    <div class="features-grid">
        <!-- Ringkasan Cerdas -->
        <div class="feature-card">
            <div class="feature-icon">📄</div>
            <div class="feature-title">Ringkasan Cerdas</div>
            <div class="feature-desc">Ubah ratusan halaman dokumen PDF menjadi poin-poin paling penting yang mudah dipelajari dalam hitungan detik.</div>
            <div class="feature-tags">
                <span class="tag tag-green">PDF</span>
                <span class="tag tag-blue">DOCX</span>
                <span class="tag tag-orange">PPT</span>
            </div>
        </div>

        <!-- Center visual -->
        <div class="feature-card" style="padding:0;overflow:hidden;">
            <div class="feature-image-placeholder">
                <div style="text-align:center;">
                    <div style="font-size:3rem;margin-bottom:8px;">🤖</div>
                    <div style="font-size:0.75rem;color:#2d6a4f;font-weight:700;">AI Engine Aktif</div>
                </div>
            </div>
            <div style="padding:20px;">
                <div class="feature-title" style="font-size:0.85rem;">Didukung Teknologi AI</div>
                <div class="feature-desc" style="font-size:0.8rem;">Powered by BSI AI Engine yang dirancang khusus untuk kebutuhan akademik mahasiswa Indonesia.</div>
            </div>
        </div>

        <!-- Kuis Otomatis -->
        <div class="feature-card accent" style="grid-row:1/3;">
            <div class="feature-icon">📝</div>
            <div class="feature-title">Kuis Otomatis</div>
            <div class="feature-desc">Uji pemahaman Anda dengan soal latihan yang dibuat langsung dari materi kuliah. Dilengkapi dengan skor dan pembahasan jawaban.</div>
            <div style="margin-top:20px;background:rgba(255,255,255,0.15);border-radius:12px;padding:16px;">
                <div style="font-size:0.75rem;font-weight:700;color:rgba(255,255,255,0.9);margin-bottom:10px;">Contoh Soal AI</div>
                <div style="font-size:0.75rem;color:rgba(255,255,255,0.85);line-height:1.5;margin-bottom:10px;">Apa perbedaan utama antara supervised dan unsupervised learning?</div>
                <div style="display:flex;flex-direction:column;gap:6px;">
                    <div style="background:rgba(255,255,255,0.1);border-radius:6px;padding:7px 10px;font-size:0.7rem;color:rgba(255,255,255,0.7);">○ Jumlah data yang digunakan</div>
                    <div style="background:rgba(255,255,255,0.25);border-radius:6px;padding:7px 10px;font-size:0.7rem;color:#fff;font-weight:600;">✓ Ketersediaan label data</div>
                    <div style="background:rgba(255,255,255,0.1);border-radius:6px;padding:7px 10px;font-size:0.7rem;color:rgba(255,255,255,0.7);">○ Kecepatan pemrosesan</div>
                </div>
            </div>
        </div>

        <!-- Tanya Jawab Materi -->
        <div class="feature-card dark">
            <div class="feature-icon">💬</div>
            <div class="feature-title">Tanya Jawab Materi</div>
            <div class="feature-desc">Bingung dengan istilah teknis? Tanya langsung ke AI kami dan dapatkan jawaban akurat berdasarkan konteks dokumen Anda.</div>
        </div>

        <!-- Pustaka Terpusat -->
        <div class="feature-card dark">
            <div class="feature-icon">📚</div>
            <div class="feature-title">Pustaka Terpusat</div>
            <div class="feature-desc">Simpan semua koleksi bahan ajar Anda di satu tempat yang aman dan bisa diakses kapan pun, di mana pun.</div>
            <div class="feature-tags" style="margin-top:12px;">
                <span class="tag" style="background:rgba(92,184,92,0.15);color:#7dce7d;">+17</span>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section">
    <h2 class="cta-title">Siap untuk Mengubah Cara Anda Belajar?</h2>
    <p class="cta-subtitle">Bergabunglah dengan ribuan mahasiswa yang telah meningkatkan efisiensi belajar mereka dengan BSI Note Book.</p>
    <div class="cta-actions">
        <a href="{{ route('register') }}" class="btn-cta-primary">
            Mulai Gratis Sekarang ✦
        </a>
        <a href="{{ route('login') }}" class="btn-cta-secondary">
            Hubungi Tim Kami →
        </a>
    </div>
</section>

<!-- Footer -->
<footer class="footer">
    <div class="footer-top">
        <div>
            <div class="footer-brand">
                <div style="width:28px;height:28px;background:linear-gradient(135deg,#5cb85c,#27ae60);border-radius:6px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:0.8rem;">📒</div>
                BSI Note Book
            </div>
            <div class="footer-tagline">Platform belajar cerdas berbasis AI untuk mahasiswa Indonesia.</div>
            <div class="footer-social">
                <a href="#" class="social-btn">𝕏</a>
                <a href="#" class="social-btn">f</a>
                <a href="#" class="social-btn">in</a>
            </div>
        </div>
        <ul class="footer-links">
            <li><a href="#">Privasi</a></li>
            <li><a href="#">Syarat & Ketentuan</a></li>
            <li><a href="#">Kontak</a></li>
            <li><a href="#">Blog</a></li>
            <li><a href="#">Pusat Bantuan</a></li>
        </ul>
    </div>
    <div class="footer-bottom">
        © {{ date('Y') }} BSI Note Book. Platform belajar cerdas untuk mahasiswa Indonesia.
    </div>
</footer>

<script>
// Navbar scroll effect
window.addEventListener('scroll', () => {
    document.getElementById('navbar').classList.toggle('scrolled', window.scrollY > 20);
});
</script>
</body>
</html>
