<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notebook Baru – BSI Note Book</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/library-light.css?v=3') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard-light.css') }}">
    <style>
        @keyframes slideInUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
<div class="dash-layout">

    <!-- App Sidebar (Global Navigation) -->
    <aside class="light-sidebar">
        <div class="light-sidebar-brand">
            <div class="logo-box">📒</div>
            <div>
                <div class="light-sidebar-brand-name">BSI Note Book</div>
                <div class="light-sidebar-brand-sub">Asisten Studi AI</div>
            </div>
        </div>
        
        <nav class="light-sidebar-nav">
            <a href="{{ route('dashboard') }}" class="light-nav-item active" style="white-space: nowrap;">
                <span style="font-size:1.1rem;width:24px;text-align:center;">📓</span> Notebook Baru
            </a>
            <a href="{{ route('library') }}" class="light-nav-item" style="white-space: nowrap;">
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
            <button type="button" class="light-upload-btn" onclick="document.getElementById('dash-upload-input').click();">
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

    <div class="dash-main">

    <!-- Header -->
    <header class="dash-header">
        <div class="dash-header-left">
            <a href="{{ route('home') }}" class="dash-header-brand">
                <span style="font-size:1.4rem;">📖</span> BSI Note Book
            </a>
            <span class="dash-header-divider">|</span>
            <span class="dash-header-context">
                @if($activeMaterial)
                    Materi Kuliah - {{ $activeMaterial->title }}
                @else
                    Pilih Materi Kuliah
                @endif
            </span>
        </div>

        <div class="dash-header-center">
            <a href="{{ route('dashboard') }}" class="dash-nav-link active">Beranda</a>
            <a href="{{ route('library') }}" class="dash-nav-link">Perpustakaan</a>
        </div>

        <div class="dash-header-right">
            <button class="dash-icon-btn">
                🔔
                <div class="light-icon-badge" style="position:absolute;top:8px;right:8px;width:8px;height:8px;background:#e53e3e;border-radius:50%;"></div>
            </button>
            <button class="dash-icon-btn">⚙</button>
            <div style="width:36px;height:36px;border-radius:50%;background:#e2e8f0;display:flex;align-items:center;justify-content:center;font-weight:700;color:#1a1a2e;overflow:hidden;">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
        </div>
    </header>

    <!-- Main Body (3 Columns) -->
    <div class="dash-body">

        <!-- Left Sidebar: Sumber Materi -->
        <aside class="dash-sidebar-left">
            <div class="sidebar-section-title">NOTEBOOK BARU</div>
            
            <button type="button" class="upload-dotted-box" onclick="document.getElementById('dash-upload-input').click();">
                <span class="upload-icon">📄</span>
                Unggah Materi Baru
            </button>

            <div class="file-list">
                @forelse($materials as $material)
                    <a href="{{ route('dashboard', ['material' => $material->id]) }}" 
                       class="file-item {{ $activeMaterial && $activeMaterial->id === $material->id ? 'active' : '' }}">
                        <span class="file-icon">
                            @if($material->file_type === 'pdf') 📄
                            @elseif(in_array($material->file_type, ['doc','docx'])) 📝
                            @else 🔗
                            @endif
                        </span>
                        <div class="file-details">
                            <div class="file-title">{{ $material->title }}</div>
                            <div class="file-meta">
                                @if($material->status === 'indexed')
                                    ● Terindeks
                                @else
                                    ● {{ $material->status_label }}
                                @endif
                                <span style="margin-left:auto;opacity:0.6;font-size:0.6rem;">{{ $material->created_at->diffForHumans(null, true, true) }}</span>
                            </div>
                        </div>
                    </a>
                @empty
                    <div style="font-size:0.8rem;color:#888;text-align:center;margin-top:20px;">Belum ada materi</div>
                @endforelse
            </div>
            
            <div style="margin-top:auto;padding-top:20px;">
                <a href="{{ route('about') }}" style="display:flex;align-items:center;gap:10px;font-size:0.85rem;color:#555;text-decoration:none;">
                    <span style="font-size:1.1rem;">❓</span> Bantuan
                </a>
            </div>
        </aside>

        <!-- Center: Main Content -->
        <main class="dash-center">
            @if($activeMaterial)
                <div class="top-actions">
                    <div class="top-actions-left">
                        <span class="pill-solid">Summary AI</span>
                        <span class="pill-outline">Reading Time: {{ rand(3, 15) }} min</span>
                    </div>
                    <!-- Form Bikin Soal (sama seperti generate quiz) -->
                    <form action="{{ route('quiz.generate', $activeMaterial->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="topic" value="{{ $activeMaterial->title }}">
                        <input type="hidden" name="count" value="10">
                        <button type="submit" class="btn-dark">
                            <span style="font-size:1.1rem;">📝</span> Bikin Soal
                        </button>
                    </form>
                </div>

                <div class="expansion-section">
                    <div class="expansion-title">Ekspansi Materi AI</div>
                    <div class="expansion-grid">
                        <a href="#" class="exp-card blue">
                            <div class="exp-icon">🎙️</div>
                            Ringkasan Audio<br>(Podcast)
                            <div class="exp-arrow">›</div>
                        </a>
                        <a href="#" class="exp-card green">
                            <div class="exp-icon">📊</div>
                            Slide Deck (PPT)
                            <div class="exp-arrow">›</div>
                        </a>
                        <a href="#" class="exp-card gray">
                            <div class="exp-icon">🗺️</div>
                            Peta Pikiran (Mind<br>Map)
                            <div class="exp-arrow">›</div>
                        </a>
                        <a href="#" class="exp-card red">
                            <div class="exp-icon">📇</div>
                            Flashcards
                            <div class="exp-arrow">›</div>
                        </a>
                        <a href="#" class="exp-card blue">
                            <div class="exp-icon">📉</div>
                            Infografis
                            <div class="exp-arrow">›</div>
                        </a>
                    </div>
                </div>

                <!-- Source PDF Panel -->
                <div id="summary-card" style="display:none; margin-top:16px; border-radius:16px; overflow:hidden; box-shadow:0 4px 24px rgba(0,0,0,0.08); border:1px solid #e2e8f0; animation: slideInUp 0.3s ease;">
                    <!-- Panel Header -->
                    <div style="background:linear-gradient(135deg,#2e7d32,#4caf50); padding:16px 20px; display:flex; align-items:center; justify-content:space-between;">
                        <div style="display:flex;align-items:center;gap:10px;">
                            <span style="font-size:1.3rem;">📄</span>
                            <div>
                                <div style="color:#fff;font-weight:700;font-size:0.95rem;">Sumber dari PDF</div>
                                <div style="color:rgba(255,255,255,0.75);font-size:0.78rem;" id="source-location">Memuat lokasi...</div>
                            </div>
                        </div>
                        <button onclick="document.getElementById('summary-card').style.display='none'" style="background:rgba(255,255,255,0.2);border:none;color:#fff;width:28px;height:28px;border-radius:50%;cursor:pointer;font-size:1rem;display:flex;align-items:center;justify-content:center;">✕</button>
                    </div>
                    <!-- Panel Body -->
                    <div style="background:#fff; padding:20px;">
                        <div style="background:#f0fdf4; border-left:4px solid #4caf50; border-radius:0 8px 8px 0; padding:16px 18px; margin-bottom:0;">
                            <div style="font-size:0.78rem;font-weight:600;color:#2e7d32;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:8px;">📌 Kutipan Teks</div>
                            <p id="source-content" style="font-size:0.92rem;line-height:1.75;color:#1a1a2e;margin:0;"></p>
                        </div>
                    </div>
                </div>
            @else
                <!-- Empty State -->
                <div style="flex:1;display:flex;flex-direction:column;align-items:center;justify-content:center;color:#888;">
                    <div style="font-size:4rem;margin-bottom:16px;">📚</div>
                    <h2 style="color:#1a1a2e;margin-bottom:8px;">Pilih atau Unggah Materi</h2>
                    <p style="font-size:0.9rem;">Silakan pilih materi di sidebar kiri untuk melihat ringkasan AI.</p>
                </div>
            @endif
        </main>

        <!-- Right Sidebar: AI Chat -->
        <aside class="dash-sidebar-right">
            <div class="chat-light-header">
                <div class="chat-light-title">
                    <div class="chat-light-avatar">🤖</div>
                    <div>
                        <div>Asisten Studi AI</div>
                        <div class="chat-light-subtitle">Siap membantu belajarmu</div>
                    </div>
                </div>
                <button style="background:none;border:none;font-size:1rem;color:#888;cursor:pointer;">🪄</button>
            </div>

            <div class="chat-light-messages" id="chat-messages">
                @if($activeMaterial)
                    <div class="chat-light-bubble ai">
                        Halo! Saya sudah mempelajari modul {{ $activeMaterial->title }}. Apakah ada bagian tertentu yang ingin kamu tanyakan atau butuh penjelasan lebih detail?
                        <div class="chat-light-time">Baru saja</div>
                    </div>
                @else
                    <div class="chat-light-bubble ai">
                        Halo! Saya Asisten AI Anda. Unggah materi di sebelah kiri, dan saya siap membantu meringkas dan menjelaskannya untuk Anda.
                        <div class="chat-light-time">Baru saja</div>
                    </div>
                @endif
            </div>

            <div class="chat-light-input-area">
                <div class="chat-light-input-box">
                    <input type="text" class="chat-light-input" id="chat-input" placeholder="Tanya tentang materi..." onkeypress="if(event.key === 'Enter') sendChatMessage()">
                    <button class="chat-light-send" id="chat-send" onclick="sendChatMessage()">➤</button>
                </div>
                <div class="chat-powered">POWERED BY BSI AI ENGINE</div>
            </div>
        </aside>
    </div>
    
    </div> <!-- Close dash-main -->
</div>

<!-- Upload Form (Hidden) -->
<form id="dash-upload-form" action="{{ route('materials.store') }}" method="POST" enctype="multipart/form-data" style="display: none;">
    @csrf
    <input type="hidden" name="redirect_to" value="dashboard">
    <input type="file" id="dash-upload-input" name="file" accept=".pdf,.doc,.docx,.txt,.pptx" onchange="submitDashUpload()">
</form>

<script>
function submitDashUpload() {
    const input = document.getElementById('dash-upload-input');
    if (input.files && input.files.length > 0) {
        document.querySelector('.upload-dotted-box').style.opacity = '0.5';
        document.querySelector('.upload-dotted-box').innerText = 'Mengunggah...';
        document.getElementById('dash-upload-form').submit();
    }
}

// Chat functionality
const activeMaterialId = {{ $activeMaterial ? $activeMaterial->id : 'null' }};
const chatMessages = document.getElementById('chat-messages');
const chatInput = document.getElementById('chat-input');
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

async function sendChatMessage() {
    const message = chatInput.value.trim();
    if (!message) return;

    // Add user message to UI
    appendMessage('user', message);
    chatInput.value = '';

    // Scroll to bottom
    chatMessages.scrollTop = chatMessages.scrollHeight;

    // Show loading
    const loadingId = 'loading-' + Date.now();
    appendMessage('ai', 'Sedang memikirkan...', loadingId);
    chatMessages.scrollTop = chatMessages.scrollHeight;

    try {
        const response = await fetch("{{ route('chat.send') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                message: message,
                material_id: activeMaterialId
            })
        });

        const data = await response.json();
        
        // Remove loading
        document.getElementById(loadingId).remove();

        if (data.success) {
            appendMessage('ai', data.message.content, null, data.message.has_source, data.message.source_text);
        } else {
            appendMessage('ai', 'Maaf, terjadi kesalahan.');
        }
        chatMessages.scrollTop = chatMessages.scrollHeight;

    } catch (error) {
        document.getElementById(loadingId).remove();
        appendMessage('ai', 'Koneksi terputus.');
    }
}

function appendMessage(role, content, id = null, hasSource = false, sourceText = '') {
    const div = document.createElement('div');
    div.className = `chat-light-bubble ${role}`;
    if (id) div.id = id;

    let html = content;
    
    if (hasSource) {
        const encodedSource = encodeURIComponent(sourceText);
        html += `<br><br><a href="#" class="chat-action-btn" style="display:inline-flex;align-items:center;gap:6px;background:#f0fdf4;color:#2e7d32;border:1px solid #bbf7d0;padding:5px 12px;border-radius:999px;font-size:0.78rem;font-weight:600;text-decoration:none;transition:all 0.2s;" onmouseover="this.style.background='#dcfce7'" onmouseout="this.style.background='#f0fdf4'" onclick="showSource('${encodedSource}'); return false;">📄 Lihat Sumber</a>`;
    }

    html += `<div class="chat-light-time">Baru saja</div>`;
    div.innerHTML = html;
    chatMessages.appendChild(div);
}

function showSource(encodedText) {
    const card = document.getElementById('summary-card');
    const fullText = decodeURIComponent(encodedText);

    // Parse lokasi (baris pertama) dan isi kutipan
    const lines = fullText.split('\n');
    const location = lines[0] || 'Dokumen PDF';
    const content = lines.slice(1).join(' ').trim() || fullText;

    document.getElementById('source-location').textContent = location;
    document.getElementById('source-content').textContent = content;

    card.style.display = 'block';
    card.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}
</script>
</body>
</html>
