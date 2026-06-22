<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Kuis – BSI Note Book</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/library-light.css?v=3') }}">
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
            <a href="{{ route('quiz.index') }}" class="light-nav-item" style="white-space: nowrap;">
                <span style="font-size:1.1rem;width:24px;text-align:center;">📝</span> Quiz AI
            </a>
            <a href="{{ route('history') }}" class="light-nav-item active" style="white-space: nowrap;">
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
            <div class="light-header-title">Riwayat Kuis</div>
            
            <div class="light-search-container">
                <span class="light-search-icon">🔍</span>
                <input type="text" class="light-search-input" placeholder="Cari riwayat kuis...">
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
            <div class="light-content-inner" style="max-width: 860px; margin: 0 auto;">
                
                <div style="margin-bottom:32px;">
                    <h1 style="font-size:1.8rem;font-weight:800;color:#1a1a2e;margin-bottom:8px;">Rekam Jejak Belajarmu</h1>
                    <p style="color:#666;font-size:0.95rem;">Lihat kembali semua hasil kuis yang pernah kamu kerjakan untuk mengukur perkembanganmu.</p>
                </div>

                @if($attempts->isEmpty())
                    <div style="display:flex;flex-direction:column;align-items:center;justify-content:center;padding:80px 0;background:#fff;border-radius:24px;border:1px solid rgba(0,0,0,0.05);box-shadow:0 4px 20px rgba(0,0,0,0.02);">
                        <div style="font-size:4rem;margin-bottom:16px;">📝</div>
                        <h2 style="color:#1a1a2e;margin-bottom:8px;">Belum Ada Riwayat</h2>
                        <p style="color:#888;">Kamu belum pernah mengerjakan kuis. Mari mulai tantangan pertamamu!</p>
                        <a href="{{ route('quiz.index') }}" class="light-upload-btn" style="margin-top:20px;display:inline-flex;width:auto;padding:12px 32px;text-decoration:none;">
                            Mulai Kuis AI
                        </a>
                    </div>
                @else
                    <div style="display:flex;flex-direction:column;gap:16px;">
                        @foreach($attempts as $attempt)
                        <div style="background:#fff;border-radius:20px;padding:24px;display:flex;align-items:center;gap:24px;border:1px solid rgba(0,0,0,0.05);box-shadow:0 4px 20px rgba(0,0,0,0.02);transition:all 0.2s;"
                             onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 12px 30px rgba(0,0,0,0.06)'"
                             onmouseout="this.style.transform='';this.style.boxShadow='0 4px 20px rgba(0,0,0,0.02)'">

                            <!-- Score Circle -->
                            <div style="width:64px;height:64px;border-radius:50%;background:{{ $attempt->score >= 80 ? '#f0fdf4' : ($attempt->score >= 60 ? '#fff7ed' : '#fef2f2') }};display:flex;align-items:center;justify-content:center;flex-shrink:0;border:2px solid {{ $attempt->score >= 80 ? '#22c55e' : ($attempt->score >= 60 ? '#f97316' : '#ef4444') }};">
                                <span style="font-size:1.2rem;font-weight:900;color:{{ $attempt->score >= 80 ? '#166534' : ($attempt->score >= 60 ? '#9a3412' : '#991b1b') }};">{{ $attempt->score }}<span style="font-size:0.8rem;">%</span></span>
                            </div>

                            <!-- Info -->
                            <div style="flex:1;min-width:0;">
                                <div style="font-size:1.1rem;font-weight:800;color:#1a1a2e;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;margin-bottom:4px;">
                                    {{ $attempt->quiz->title ?? 'Kuis' }}
                                </div>
                                <div style="font-size:0.85rem;color:#666;display:flex;align-items:center;gap:6px;">
                                    <span>📚</span> {{ $attempt->quiz->material->title ?? '-' }}
                                </div>
                                <div style="display:flex;align-items:center;gap:16px;margin-top:12px;">
                                    <div style="display:flex;align-items:center;gap:6px;font-size:0.8rem;color:#555;background:#f1f5f9;padding:4px 12px;border-radius:12px;font-weight:600;">
                                        <span style="color:#22c55e;">✓</span> {{ $attempt->correct_count }} / {{ $attempt->quiz->total_questions }} Benar
                                    </div>
                                    <div style="font-size:0.8rem;color:#888;font-weight:500;">
                                        🕐 {{ $attempt->finished_at->format('d M Y, H:i') }}
                                    </div>
                                </div>
                            </div>

                            <!-- Status Badge & Action -->
                            <div style="flex-shrink:0;text-align:right;display:flex;flex-direction:column;align-items:flex-end;gap:12px;">
                                <span style="display:inline-block;padding:6px 16px;border-radius:20px;font-size:0.8rem;font-weight:700;
                                    background:{{ $attempt->score >= 80 ? '#dcfce7' : ($attempt->score >= 60 ? '#ffedd5' : '#fee2e2') }};
                                    color:{{ $attempt->score >= 80 ? '#166534' : ($attempt->score >= 60 ? '#9a3412' : '#991b1b') }};">
                                    {{ $attempt->score >= 80 ? '🎉 Luar Biasa' : ($attempt->score >= 60 ? '👍 Bagus' : '💪 Perlu Latihan') }}
                                </span>
                                
                                <a href="{{ route('quiz.result', ['quiz' => $attempt->quiz_id, 'attempt' => $attempt->id]) }}"
                                   style="font-size:0.85rem;font-weight:700;color:#1a1a2e;text-decoration:none;display:flex;align-items:center;gap:4px;padding:6px 12px;border-radius:8px;transition:background 0.2s;"
                                   onmouseover="this.style.background='#f1f5f9'"
                                   onmouseout="this.style.background='transparent'">
                                    Lihat Detail →
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    @if($attempts->hasPages())
                        <div style="margin-top:32px;display:flex;justify-content:center;">
                            {{ $attempts->links() }}
                        </div>
                    @endif
                @endif
                
            </div>
        </div>
    </div>
</div>
</body>
</html>
