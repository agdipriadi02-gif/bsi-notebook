<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk – BSI Note Book</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        .btn-google {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            width: 100%;
            padding: 12px 16px;
            background-color: #fff;
            color: #333;
            border: 1px solid rgba(0,0,0,0.1);
            border-radius: 12px;
            font-size: 0.95rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
            font-family: 'Inter', sans-serif;
            margin-top: 16px;
        }
        .btn-google:hover {
            background-color: #fafafa;
            border-color: rgba(0,0,0,0.15);
            box-shadow: 0 4px 6px rgba(0,0,0,0.04);
            transform: translateY(-1px);
        }
        .btn-google svg {
            width: 20px;
            height: 20px;
        }
    </style>
</head>
<body style="background: linear-gradient(135deg, #dcfce7 0%, #e0e7ff 100%); display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0; font-family: 'Inter', sans-serif;">

<div style="width: 100%; max-width: 420px; padding: 40px;">
    <!-- Logo & Title -->
    <div style="text-align: center; margin-bottom: 40px;">
        <div style="width: 48px; height: 48px; background: linear-gradient(135deg, var(--primary), var(--accent-dark)); border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; font-size: 1.5rem; color: #fff; margin-bottom: 20px; box-shadow: 0 8px 24px rgba(92,184,92,0.25);">📒</div>
        <h1 style="font-size: 1.5rem; font-weight: 800; color: #1a1a2e; margin: 0 0 8px;">Selamat Datang Di BSI NOTEBOOK</h1>
        <p style="font-size: 0.9rem; color: #888; margin: 0;">Masuk untuk melanjutkan belajar Anda.</p>
    </div>

    @if(session('error'))
        <div class="alert alert-error" style="margin-bottom: 24px; border-radius: 12px;">
            <span class="alert-icon">⚠</span>
            {{ session('error') }}
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success" style="margin-bottom: 24px; border-radius: 12px; background-color: #dcfce7; color: #166534; padding: 16px; display: flex; align-items: center; gap: 12px; border: 1px solid #bbf7d0;">
            <span class="alert-icon">✓</span>
            {{ session('success') }}
        </div>
    @endif

    <!-- Form Box -->
    <div style="background: #fff; padding: 32px; border-radius: 20px; box-shadow: 0 10px 40px rgba(0,0,0,0.04); border: 1px solid rgba(0,0,0,0.04);">
        <form method="POST" action="{{ route('login') }}" id="login-form">
            @csrf
            
            <div class="form-group" style="margin-bottom: 20px;">
                <label class="form-label" for="email" style="font-weight: 600; color: #333;">Alamat Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    class="form-input {{ $errors->has('email') ? 'error' : '' }}"
                    placeholder="nama@email.com"
                    value="{{ old('email') }}"
                    required
                    autocomplete="email"
                    style="border-radius: 12px; padding: 12px 16px; background: #fafafa; border-color: rgba(0,0,0,0.06);"
                >
                @error('email')
                    <div class="form-error">⚠ {{ $message }}</div>
                @enderror
            </div>

            <div class="form-group" style="margin-bottom: 24px;">
                <label class="form-label" for="password" style="font-weight: 600; color: #333;">Password</label>
                <div style="position: relative;">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-input {{ $errors->has('password') ? 'error' : '' }}"
                        placeholder="••••••••"
                        required
                        autocomplete="current-password"
                        style="border-radius: 12px; padding: 12px 16px; background: #fafafa; border-color: rgba(0,0,0,0.06); width: 100%; box-sizing: border-box;"
                    >
                    <button type="button" onclick="togglePassword('password', this)" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #888; padding: 0; display: flex; align-items: center; justify-content: center;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                    </button>
                </div>
                @error('password')
                    <div class="form-error">⚠ {{ $message }}</div>
                @enderror
            </div>

            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:32px;">
                <label style="display:flex;align-items:center;gap:8px;font-size:0.85rem;color:#666;cursor:pointer;">
                    <input type="checkbox" name="remember" style="accent-color:var(--primary); width: 16px; height: 16px;">
                    Ingat saya
                </label>
                <a href="#" style="font-size:0.85rem;color:var(--primary);font-weight:600;text-decoration:none;">Lupa password?</a>
            </div>

            <button type="submit" class="btn-auth" id="login-btn" style="border-radius: 12px; padding: 14px; font-size: 0.95rem; letter-spacing: 0.5px; width: 100%; margin-bottom: 24px;">
                Login
            </button>
            
            <!-- Divider -->
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                <div style="flex: 1; height: 1px; background: rgba(0,0,0,0.06);"></div>
                <span style="font-size: 0.8rem; color: #888; font-weight: 500;">atau</span>
                <div style="flex: 1; height: 1px; background: rgba(0,0,0,0.06);"></div>
            </div>

            <!-- Google Login Button -->
            <a href="{{ route('auth.google') }}" class="btn-google" id="btn-google-login">
                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                </svg>
                Masuk dengan Google
            </a>

        </form>
    </div>

    <div style="text-align: center; margin-top: 32px; font-size: 0.9rem; color: #888;">
        Belum punya akun? <a href="{{ route('register') }}" style="color: var(--primary); font-weight: 600; text-decoration: none;">Daftar gratis</a>
    </div>
</div>

<script>
function togglePassword(inputId, button) {
    const input = document.getElementById(inputId);
    const svg = button.querySelector('svg');
    if (input.type === 'password') {
        input.type = 'text';
        svg.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line>';
    } else {
        input.type = 'password';
        svg.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle>';
    }
}

document.getElementById('login-form').addEventListener('submit', function() {
    const btn = document.getElementById('login-btn');
    if(btn) { btn.textContent = 'Memproses...'; btn.disabled = true; }
});

document.getElementById('btn-google-login').addEventListener('click', function() {
    this.innerHTML = '<svg style="width:20px;height:20px;animation:spin 0.8s linear infinite" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="#ccc" stroke-width="3" fill="none"/><path d="M12 2a10 10 0 0 1 10 10" stroke="#10b981" stroke-width="3" fill="none" stroke-linecap="round"/></svg> Menghubungkan...';
    this.style.pointerEvents = 'none';
});
</script>
<style>
@keyframes spin { to { transform: rotate(360deg); } }
</style>
</body>
</html>
