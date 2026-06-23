<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz AI – BSI Note Book</title>
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
            <a href="{{ route('library') }}" class="light-nav-item" style="white-space: nowrap;">
                <span style="font-size:1.1rem;width:24px;text-align:center;">📚</span> Perpustakaan Saya
            </a>
            <a href="{{ route('quiz.index') }}" class="light-nav-item active" style="white-space: nowrap;">
                <span style="font-size:1.1rem;width:24px;text-align:center;">📝</span> Quiz AI
            </a>
            <a href="{{ route('history') }}" class="light-nav-item" style="white-space: nowrap;">
                <span style="font-size:1.1rem;width:24px;text-align:center;">🕐</span> Riwayat
            </a>
        </nav>

        <div class="light-sidebar-footer">
            <a href="{{ route('library') }}" class="light-upload-btn" style="text-decoration:none;">
                <span>📤</span> Unggah Materi
            </a>
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
            <div class="light-header-title">Pilih Materi untuk Kuis</div>
            
            <div class="light-search-container">
                <span class="light-search-icon">🔍</span>
                <input type="text" class="light-search-input" placeholder="Cari materi untuk diuji...">
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
                
                @if($materials->isEmpty())
                    <div style="display:flex;flex-direction:column;align-items:center;justify-content:center;padding:100px 0;">
                        <div style="font-size:4rem;margin-bottom:16px;">📚</div>
                        <h2 style="color:#1a1a2e;margin-bottom:8px;">Belum Ada Materi</h2>
                        <p style="color:#888;">Unggah materi di Perpustakaan terlebih dahulu untuk men-generate kuis.</p>
                        <a href="{{ route('library') }}" class="light-upload-btn" style="margin-top:20px;display:inline-flex;width:auto;">Pergi ke Perpustakaan</a>
                    </div>
                @else
                    <div style="margin-bottom:32px;">
                        <h2 style="font-size:1.5rem;font-weight:800;color:#1a1a2e;">Dari materi mana Anda ingin diuji?</h2>
                        <p style="color:#666;font-size:0.9rem;margin-top:8px;">Pilih salah satu PDF yang sudah diunggah, dan AI akan otomatis membuatkan soal latihan.</p>
                    </div>

                    <!-- Grid -->
                    <div class="new-library-grid">
                        @foreach($materials as $material)
                            <div class="new-lib-card">
                                <div class="new-lib-cover" style="height:120px;">
                                    <span class="new-lib-icon">
                                        @if($material->file_type === 'pdf') 📄
                                        @elseif(in_array($material->file_type, ['doc','docx'])) 📝
                                        @else 📄
                                        @endif
                                    </span>
                                </div>
                                <div class="new-lib-body">
                                    <div class="new-lib-title">{{ $material->title }}</div>
                                    <div class="new-lib-date">Diunggah {{ $material->created_at->format('d M Y') }}</div>
                                    
                                    @if($material->status === 'indexed')
                                        <form action="{{ route('quiz.generate', $material->id) }}" method="POST" style="margin-top:auto;">
                                            @csrf
                                            <input type="hidden" name="topic" value="{{ $material->title }}">
                                            <input type="hidden" name="count" value="10">
                                            <button type="submit" class="new-lib-action" style="width:100%;background:#1a1a2e;color:#fff;border:none;">
                                                📝 Generate Kuis AI
                                            </button>
                                        </form>
                                    @else
                                        <div class="new-lib-action disabled" style="margin-top:auto;">
                                            ⏳ Sedang diindeks AI...
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if($materials->hasPages())
                        <button class="load-more-btn">
                            Lihat Lebih Banyak ⌄
                        </button>
                    @endif
                @endif
                
            </div>
        </div>
    </div>
</div>
</body>
</html>
