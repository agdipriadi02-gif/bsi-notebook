<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $quiz->title }} – BSI Note Book</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/library-light.css?v=3') }}">
    <style>
        .quiz-light-content { flex: 1; overflow-y: auto; background: #f8f9fa; padding: 32px 48px; position: relative; scroll-behavior: smooth; }
        .quiz-header-bar { display: flex; align-items: center; justify-content: space-between; padding-bottom: 24px; border-bottom: 1px solid rgba(0,0,0,0.06); margin-bottom: 32px; }
        .quiz-header-title { font-size: 1.4rem; font-weight: 800; color: #1a1a2e; margin-bottom: 4px; }
        .quiz-header-topic { font-size: 0.85rem; color: #666; font-weight: 500; }
        
        .quiz-status-right { display: flex; align-items: center; gap: 32px; }
        .quiz-progress-area { display: flex; flex-direction: column; width: 200px; }
        .quiz-progress-label { display: flex; justify-content: space-between; font-size: 0.8rem; font-weight: 600; color: #555; margin-bottom: 8px; }
        .quiz-progress-bar { height: 6px; background: #e2e8f0; border-radius: 3px; overflow: hidden; }
        .quiz-progress-fill { height: 100%; background: #2e7d32; transition: width 0.3s ease; }
        
        .quiz-timer-area { display: flex; flex-direction: column; align-items: flex-end; }
        .quiz-timer-label { font-size: 0.75rem; color: #666; font-weight: 600; margin-bottom: 4px; }
        .quiz-timer-value { font-size: 1.5rem; font-weight: 800; color: #e53e3e; font-variant-numeric: tabular-nums; }

        .question-block { display: flex; gap: 24px; margin-bottom: 48px; max-width: 900px; }
        .q-number { width: 48px; height: 48px; background: #1a202c; color: #fff; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; font-weight: 800; flex-shrink: 0; }
        .q-body { flex: 1; }
        .q-text { font-size: 1.1rem; font-weight: 700; color: #1a1a2e; margin-bottom: 8px; line-height: 1.5; }
        .q-context { font-size: 0.9rem; color: #666; margin-bottom: 24px; }

        .options-list { display: flex; flex-direction: column; gap: 12px; }
        .option-label { display: flex; align-items: center; gap: 16px; padding: 16px 20px; background: #fff; border: 1px solid rgba(0,0,0,0.1); border-radius: 12px; cursor: pointer; transition: 0.2s; }
        .option-label:hover { border-color: #cbd5e1; background: #f8fafc; }
        .option-label.selected { border-color: #2e7d32; background: #f0fdf4; }
        
        .option-radio { margin: 0; width: 20px; height: 20px; cursor: pointer; accent-color: #2e7d32; }
        .option-text { font-size: 0.95rem; color: #333; font-weight: 500; }

        /* Floating Tanya AI Widget */
        .tanya-ai-btn { position: fixed; bottom: 32px; right: 32px; background: #2e7d32; color: #fff; border: none; padding: 12px 24px; border-radius: 30px; font-size: 0.95rem; font-weight: 700; display: flex; align-items: center; gap: 8px; cursor: pointer; box-shadow: 0 4px 16px rgba(46,125,50,0.3); transition: 0.2s; z-index: 100; }
        .tanya-ai-btn:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(46,125,50,0.4); }

        .ai-chat-widget { position: fixed; bottom: 90px; right: 32px; width: 340px; background: #fff; border-radius: 16px; box-shadow: 0 10px 40px rgba(0,0,0,0.1); display: none; flex-direction: column; overflow: hidden; z-index: 100; border: 1px solid rgba(0,0,0,0.05); }
        .chat-widget-header { background: #1a202c; color: #fff; padding: 16px; display: flex; align-items: center; justify-content: space-between; }
        .chat-widget-title { display: flex; align-items: center; gap: 8px; font-size: 0.9rem; font-weight: 700; }
        .chat-close-btn { background: none; border: none; color: rgba(255,255,255,0.7); font-size: 1.2rem; cursor: pointer; }
        .chat-close-btn:hover { color: #fff; }
        .chat-widget-body { height: 300px; padding: 16px; overflow-y: auto; display: flex; flex-direction: column; gap: 12px; background: #f8f9fa; }
        .chat-widget-input { padding: 16px; border-top: 1px solid rgba(0,0,0,0.05); background: #fff; }

        .quiz-submit-bar { display: flex; justify-content: space-between; align-items: center; padding: 24px; background: #fff; border-top: 1px solid rgba(0,0,0,0.06); position: sticky; bottom: 0; z-index: 10; }
    </style>
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
            <button type="button" class="light-upload-btn" onclick="alert('Buka Perpustakaan untuk unggah materi.');">
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
    <div style="flex:1; display:flex; flex-direction:column; overflow:hidden;">
        
        <form action="{{ route('quiz.submit', $quiz->id) }}" method="POST" id="quiz-form" style="display:flex; flex-direction:column; flex:1; overflow:hidden; margin:0;">
            @csrf
            @if($attempt)
                <input type="hidden" name="attempt_id" value="{{ $attempt->id }}">
            @endif

            <div class="quiz-light-content">
                <div class="quiz-header-bar">
                    <div>
                        <div class="quiz-header-title">{{ $quiz->title }}</div>
                        <div class="quiz-header-topic">Topik: {{ $quiz->topic }}</div>
                    </div>
                    <div class="quiz-status-right">
                        <div class="quiz-progress-area">
                            <div class="quiz-progress-label">
                                <span>Progres Kuis</span>
                                <span id="progress-text">0 / {{ $questions->count() }} Soal</span>
                            </div>
                            <div class="quiz-progress-bar">
                                <div class="quiz-progress-fill" id="progress-fill" style="width:0%"></div>
                            </div>
                        </div>
                        <div class="quiz-timer-area">
                            <div class="quiz-timer-label">Sisa Waktu</div>
                            <div class="quiz-timer-value" id="timer">{{ str_pad($quiz->time_limit_minutes, 2, '0', STR_PAD_LEFT) }}:00</div>
                        </div>
                    </div>
                </div>

                <div class="questions-container">
                    @foreach($questions as $index => $question)
                        <div class="question-block" id="question-{{ $question->id }}">
                            <div class="q-number">{{ $index + 1 }}</div>
                            <div class="q-body">
                                <div class="q-text">{{ $question->question }}</div>
                                @if($question->context)
                                    <div class="q-context">{{ $question->context }}</div>
                                @endif

                                <div class="options-list">
                                    @foreach($question->options as $key => $optionText)
                                        <label class="option-label" onclick="updateProgress()">
                                            <input type="radio" class="option-radio" name="answers[{{ $question->id }}]" value="{{ $key }}">
                                            <span class="option-text">{{ $optionText }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div style="height: 100px;"></div> <!-- Padding for floating button -->
            </div>

            <div class="quiz-submit-bar">
                <div style="font-size:0.9rem; color:#666;">
                    Pastikan semua soal telah terjawab sebelum mengumpulkan kuis.
                </div>
                <button type="submit" class="light-upload-btn" style="width:auto; padding:12px 32px;">Kumpulkan Kuis</button>
            </div>
        </form>

    </div>
</div>

<script>
    function updateProgress() {
        const total = {{ $questions->count() }};
        const answered = document.querySelectorAll('input[type="radio"]:checked').length;
        document.getElementById('progress-text').innerText = answered + ' / ' + total + ' Soal';
        document.getElementById('progress-fill').style.width = (answered / total * 100) + '%';

        // Update selected class for styling
        document.querySelectorAll('.option-label').forEach(label => {
            const radio = label.querySelector('input[type="radio"]');
            if (radio && radio.checked) {
                label.classList.add('selected');
            } else {
                label.classList.remove('selected');
            }
        });
    }

    let totalSeconds = {{ $quiz->time_limit_minutes * 60 }};
    const timerEl = document.getElementById('timer');
    
    const interval = setInterval(() => {
        totalSeconds--;
        if (totalSeconds <= 0) {
            clearInterval(interval);
            document.getElementById('quiz-form').submit();
            return;
        }
        
        const m = Math.floor(totalSeconds / 60);
        const s = totalSeconds % 60;
        timerEl.innerText = String(m).padStart(2, '0') + ':' + String(s).padStart(2, '0');
    }, 1000);
</script>
</body>
</html>
