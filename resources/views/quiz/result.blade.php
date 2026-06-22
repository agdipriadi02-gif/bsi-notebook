<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Kuis – BSI Note Book</title>
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
            <div class="light-header-title">Hasil Kuis AI</div>
            
            <div class="light-search-container">
                <span class="light-search-icon">🔍</span>
                <input type="text" class="light-search-input" placeholder="Cari materi...">
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
            <div class="light-content-inner" style="max-width: 800px; margin: 0 auto; padding-top: 40px;">

                <!-- Score Card -->
                <div class="result-card">
                    <div class="result-score-circle">
                        <div class="result-score-number">{{ $attempt->score }}%</div>
                    </div>
                    <div class="result-title">
                        @if($attempt->score >= 80) 🎉 Luar Biasa!
                        @elseif($attempt->score >= 60) 👍 Bagus!
                        @else 💪 Terus Berlatih!
                        @endif
                    </div>
                    <div class="result-subtitle">
                        @if($attempt->score >= 80) Pemahaman Anda terhadap materi ini sangat baik!
                        @elseif($attempt->score >= 60) Anda sudah memahami sebagian besar materi dengan baik.
                        @else Jangan menyerah! Ulangi materi dan coba kuis lagi.
                        @endif
                    </div>
                    <div class="result-stats">
                        <div class="result-stat">
                            <div class="result-stat-value" style="color:var(--primary)">{{ $attempt->correct_count }}</div>
                            <div class="result-stat-label">Benar</div>
                        </div>
                        <div class="result-stat">
                            <div class="result-stat-value" style="color:#e53e3e">{{ $questions->count() - $attempt->correct_count }}</div>
                            <div class="result-stat-label">Salah</div>
                        </div>
                        <div class="result-stat">
                            <div class="result-stat-value">{{ $questions->count() }}</div>
                            <div class="result-stat-label">Total Soal</div>
                        </div>
                        <div class="result-stat">
                            <div class="result-stat-value">{{ $attempt->score }}%</div>
                            <div class="result-stat-label">Nilai</div>
                        </div>
                    </div>

                    <div style="display:flex;gap:12px;justify-content:center;margin-top:28px;flex-wrap:wrap;">
                        <form action="{{ route('quiz.generate', $material->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="topic" value="{{ $quiz->topic }}">
                            <input type="hidden" name="count" value="10">
                            <button type="submit" class="btn-quiz-submit">🔄 Coba Kuis Baru</button>
                        </form>
                        <a href="{{ route('dashboard', ['material' => $material->id]) }}" class="btn-quiz-nav" style="display:inline-flex;align-items:center;gap:6px;">
                            📄 Kembali ke Materi
                        </a>
                    </div>
                </div>

                <!-- Answer Review -->
                <div style="margin-bottom:16px;">
                    <h2 style="font-size:1.1rem;font-weight:800;color:#1a1a2e;margin-bottom:4px;">📋 Pembahasan Jawaban</h2>
                    <p style="font-size:0.875rem;color:#888;">Review semua soal dan jawaban yang benar</p>
                </div>

                @foreach($questions as $index => $question)
                    @php
                        $userAnswer = $attempt->answers[$question->id] ?? null;
                        $isCorrect = $userAnswer === $question->correct_answer;
                    @endphp
                    <div class="quiz-question-block" style="margin-bottom:16px;">
                        <div class="quiz-q-header">
                            <div class="quiz-q-number" style="background:{{ $isCorrect ? 'linear-gradient(135deg,#38a169,#2f855a)' : 'linear-gradient(135deg,#e53e3e,#c53030)' }};">
                                {{ $index + 1 }}
                            </div>
                            <div style="flex:1;">
                                <div class="quiz-q-text">{{ $question->question }}</div>
                                <div style="margin-top:4px;">
                                    @if($isCorrect)
                                        <span style="font-size:0.75rem;font-weight:700;color:#38a169;background:rgba(56,161,105,0.1);padding:2px 8px;border-radius:4px;">✓ Benar</span>
                                    @else
                                        <span style="font-size:0.75rem;font-weight:700;color:#e53e3e;background:rgba(229,62,62,0.08);padding:2px 8px;border-radius:4px;">✗ Salah</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="quiz-options">
                            @foreach($question->options as $key => $optionText)
                                <div class="quiz-option
                                    {{ $key === $question->correct_answer ? 'correct' : '' }}
                                    {{ $key === $userAnswer && !$isCorrect ? 'incorrect' : '' }}"
                                    style="cursor:default;">
                                    <div class="quiz-option-radio {{ in_array($key, [$question->correct_answer, $userAnswer]) ? 'selected' : '' }}"
                                        style="{{ $key === $question->correct_answer ? 'border-color:#38a169;background:#38a169;' : ($key === $userAnswer && !$isCorrect ? 'border-color:#e53e3e;background:#e53e3e;' : '') }}">
                                        @if($key === $question->correct_answer || ($key === $userAnswer && !$isCorrect))
                                            <span style="width:6px;height:6px;background:#fff;border-radius:50%;display:block;"></span>
                                        @endif
                                    </div>
                                    <span>
                                        <strong>{{ $key }}.</strong> {{ $optionText }}
                                        @if($key === $question->correct_answer)
                                            <span style="margin-left:8px;font-size:0.7rem;font-weight:700;color:#38a169;">✓ Jawaban Benar</span>
                                        @endif
                                        @if($key === $userAnswer && !$isCorrect)
                                            <span style="margin-left:8px;font-size:0.7rem;font-weight:700;color:#e53e3e;">✗ Jawaban Anda</span>
                                        @endif
                                    </span>
                                </div>
                            @endforeach
                        </div>

                        @if($question->explanation)
                            <div style="margin-top:14px;padding:12px 16px;background:rgba(33,150,243,0.06);border-left:3px solid #1976d2;border-radius:0 8px 8px 0;">
                                <div style="font-size:0.75rem;font-weight:700;color:#1976d2;margin-bottom:4px;">💡 Penjelasan</div>
                                <div style="font-size:0.85rem;color:#444;line-height:1.6;">{{ $question->explanation }}</div>
                            </div>
                        @endif
                    </div>
                @endforeach

            </div>
        </div>
    </div>
</div>
</body>
</html>
