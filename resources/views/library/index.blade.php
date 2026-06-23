<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perpustakaan Saya – BSI Note Book</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/library-light.css') }}?v={{ time() }}">
</head>
<body>
<div class="light-layout">

    <!-- App Sidebar -->
    <aside class="light-sidebar">
        <div class="light-sidebar-brand">
            <div class="logo-box">📒</div>
            <div>
                <div class="light-sidebar-brand-name">BSI Note Book</div>
                <div class="light-sidebar-brand-sub">Asisten Studi AI</div>
            </div>
        </div>
        
        <nav class="light-sidebar-nav">
            <a href="{{ route('dashboard') }}" class="light-nav-item" style="white-space: nowrap;">
                <span style="font-size:1.1rem;width:24px;text-align:center;">📓</span> Notebook Baru
            </a>
            <a href="{{ route('library') }}" class="light-nav-item active" style="white-space: nowrap;">
                <span style="font-size:1.1rem;width:24px;text-align:center;">📚</span> Perpustakaan Saya
            </a>
            <a href="{{ route('quiz.index') }}" class="light-nav-item" style="white-space: nowrap;">
                <span style="font-size:1.1rem;width:24px;text-align:center;">📝</span> Quiz AI
            </a>
            <a href="{{ route('history') }}" class="light-nav-item" style="white-space: nowrap;">
                <span style="font-size:1.1rem;width:24px;text-align:center;">🕐</span> Riwayat
            </a>
        </nav>

        <div class="light-sidebar-footer">
            <button type="button" class="light-upload-btn" onclick="document.getElementById('library-upload-input').click();">
                <span>📤</span> Unggah Materi
            </button>
            <a href="{{ route('about') }}" class="light-nav-item" style="white-space: nowrap;" style="margin-top:8px;">
                <span style="font-size:1.1rem;width:24px;text-align:center;">❓</span> Bantuan
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="light-nav-item" style="white-space: nowrap;">
                    <span style="font-size:1.1rem;width:24px;text-align:center;">↪</span> Keluar
                </button>
            </form>
        </div>
    </aside>

    <!-- Main -->
    <div class="light-main">
        <header class="light-header">
            <div class="light-header-title">Perpustakaan Saya</div>
            
            <div class="light-search-container">
                <span class="light-search-icon">🔍</span>
                <input type="text" class="light-search-input" placeholder="Cari materi atau catatan...">
            </div>

            <div class="light-header-actions">
                <button class="light-icon-btn">
                    🔔
                    <div class="light-icon-badge"></div>
                </button>
                <button class="light-icon-btn">⚙</button>
                <div style="width:40px;height:40px;border-radius:50%;background:#e8eaf6;display:flex;align-items:center;justify-content:center;font-weight:700;color:#1a1a2e;overflow:hidden;">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
            </div>
        </header>

        <div class="light-content">
            <div class="light-content-inner">
                
                <!-- Filter Bar -->
                <div class="filter-bar">
                    <div class="filter-pills">
                        <button class="filter-pill active">Semua Materi</button>
                        <button class="filter-pill">Terbaru</button>
                        <button class="filter-pill">PDF</button>
                        <button class="filter-pill">Link Web</button>
                        <button class="filter-pill">Favorit</button>
                    </div>
                    <div class="filter-stats">
                        <div class="filter-stat-item">📄 {{ $materials->total() }} Materi</div>
                        <div class="filter-stat-item">🗄️ 2.4 GB</div>
                    </div>
                </div>

                <!-- Grid -->
                <div class="new-library-grid">
                    @foreach($materials as $material)
                        <div class="new-lib-card">
                            <div class="new-lib-cover">
                                <span class="new-lib-icon">
                                    @if($material->file_type === 'pdf') 📄
                                    @elseif(in_array($material->file_type, ['doc','docx'])) 📝
                                    @elseif(in_array($material->file_type, ['ppt','pptx'])) 📊
                                    @else 📄
                                    @endif
                                </span>
                                <button class="new-lib-menu-btn">⋮</button>
                            </div>
                            <div class="new-lib-body">
                                <div class="new-lib-title">{{ $material->title }}</div>
                                <div class="new-lib-date">Diunggah {{ $material->created_at->format('d M Y') }}</div>
                                
                                @if($material->status === 'indexed')
                                    <div class="new-lib-status">
                                        Ringkasan AI 
                                        <span class="status-badge">✓ Selesai</span>
                                    </div>
                                    <div class="status-progress-bar">
                                        <div class="status-progress-fill" style="width: 100%; background: #38a169;"></div>
                                    </div>
                                    <a href="{{ route('dashboard', ['material' => $material->id]) }}" class="new-lib-action" style="text-decoration:none;">
                                        📖 Pelajari Materi
                                    </a>
                                @else
                                    <div class="new-lib-status">
                                        Ringkasan AI 
                                        <span class="status-badge processing">● Memproses...</span>
                                    </div>
                                    <div class="status-progress-bar">
                                        <div class="status-progress-fill" style="width: 45%;"></div>
                                    </div>
                                    <div class="new-lib-action disabled">
                                        ⏳ Menunggu AI
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach

                    <!-- Placeholder -->
                    <button type="button" class="new-lib-placeholder" style="cursor:pointer;" onclick="document.getElementById('library-upload-input').click();">
                        <div class="new-lib-placeholder-icon">+</div>
                        <div class="new-lib-placeholder-title">Materi Baru</div>
                        <div class="new-lib-placeholder-sub">Klik untuk upload PDF atau dokumen</div>
                    </button>
                </div>

                @if($materials->hasPages())
                    <button class="load-more-btn">
                        Lihat Lebih Banyak ⌄
                    </button>
                @endif
                
            </div>
            
            <!-- Footer -->
            <footer class="light-footer">
                <div class="light-footer-brand">BSI Note Book</div>
                <div class="light-footer-links">
                    <a href="#">Privasi</a>
                    <a href="#">Syarat & Ketentuan</a>
                    <a href="#">Kontak</a>
                </div>
                <div class="light-footer-copy">
                    © 2026 BSI Note Book. Cerdas Bersama AI.
                </div>
            </footer>
        </div>
    </div>
</div>

<form id="library-upload-form" action="{{ route('materials.store') }}" method="POST" enctype="multipart/form-data" style="display: none;">
    @csrf
    <input type="hidden" name="redirect_to" value="library">
    <input type="file" id="library-upload-input" name="file" accept=".pdf,.doc,.docx,.txt,.pptx" onchange="submitLibraryUpload()">
</form>

<script>
function submitLibraryUpload() {
    const input = document.getElementById('library-upload-input');
    if (input.files && input.files.length > 0) {
        document.querySelectorAll('.light-upload-btn, .new-lib-placeholder').forEach(el => {
            el.style.opacity = '0.5';
            el.style.pointerEvents = 'none';
        });
        document.getElementById('library-upload-form').submit();
    }
}
</script>
</body>
</html>
