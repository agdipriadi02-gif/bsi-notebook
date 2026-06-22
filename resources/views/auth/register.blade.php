<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar – BSI Note Book</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body style="background: linear-gradient(135deg, #dcfce7 0%, #e0e7ff 100%); display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0; font-family: 'Inter', sans-serif;">

<div style="width: 100%; max-width: 440px; padding: 40px;">
    <!-- Logo & Title -->
    <div style="text-align: center; margin-bottom: 36px;">
        <div style="width: 48px; height: 48px; background: linear-gradient(135deg, var(--primary), var(--accent-dark)); border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; font-size: 1.5rem; color: #fff; margin-bottom: 16px; box-shadow: 0 8px 24px rgba(92,184,92,0.25);">📒</div>
        <h1 style="font-size: 1.5rem; font-weight: 800; color: #1a1a2e; margin: 0 0 8px;">Buat Akun Baru</h1>
        <p style="font-size: 0.9rem; color: #888; margin: 0;">Mulai pengalaman belajar cerdas dengan AI.</p>
    </div>

    <!-- Form Box -->
    <div style="background: #fff; padding: 32px; border-radius: 20px; box-shadow: 0 10px 40px rgba(0,0,0,0.04); border: 1px solid rgba(0,0,0,0.04);">
        <form method="POST" action="{{ route('register') }}">
            @csrf
            
            <div class="form-group" style="margin-bottom: 20px;">
                <label class="form-label" for="name" style="font-weight: 600; color: #333;">Nama Lengkap</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    class="form-input {{ $errors->has('name') ? 'error' : '' }}"
                    placeholder="Nama lengkap Anda"
                    value="{{ old('name') }}"
                    required
                    autocomplete="name"
                    style="border-radius: 12px; padding: 12px 16px; background: #fafafa; border-color: rgba(0,0,0,0.06);"
                >
                @error('name')
                    <div class="form-error">⚠ {{ $message }}</div>
                @enderror
            </div>

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

            <div class="form-group" style="margin-bottom: 20px;">
                <label class="form-label" for="password" style="font-weight: 600; color: #333;">Password</label>
                <div style="position: relative;">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-input {{ $errors->has('password') ? 'error' : '' }}"
                        placeholder="••••••••"
                        required
                        autocomplete="new-password"
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

            <div class="form-group" style="margin-bottom: 28px;">
                <label class="form-label" for="password_confirmation" style="font-weight: 600; color: #333;">Konfirmasi Password</label>
                <div style="position: relative;">
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        class="form-input"
                        placeholder="Ulangi password"
                        required
                        autocomplete="new-password"
                        style="border-radius: 12px; padding: 12px 16px; background: #fafafa; border-color: rgba(0,0,0,0.06); width: 100%; box-sizing: border-box;"
                    >
                    <button type="button" onclick="togglePassword('password_confirmation', this)" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #888; padding: 0; display: flex; align-items: center; justify-content: center;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn-auth" id="register-btn" style="border-radius: 12px; padding: 14px; font-size: 0.95rem; letter-spacing: 0.5px;">
                Buat Akun Sekarang
            </button>

            <div style="margin-top: 20px; font-size: 0.8rem; color: #888; text-align: center; line-height: 1.5;">
                Dengan mendaftar, Anda menyetujui<br>
                <a href="#" style="color: var(--primary); font-weight: 600; text-decoration: none;">Syarat & Ketentuan</a> dan <a href="#" style="color: var(--primary); font-weight: 600; text-decoration: none;">Kebijakan Privasi</a> kami.
            </div>
        </form>
    </div>

    <div style="text-align: center; margin-top: 32px; font-size: 0.9rem; color: #888;">
        Sudah punya akun? <a href="{{ route('login') }}" style="color: var(--primary); font-weight: 600; text-decoration: none;">Masuk di sini</a>
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

document.querySelector('form').addEventListener('submit', function() {
    const btn = document.getElementById('register-btn');
    if(btn) { btn.textContent = 'Membuat akun...'; btn.disabled = true; }
});
</script>
</body>
</html>
