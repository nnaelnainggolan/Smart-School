<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#173f35">
    <title>Login — SMA Smart School</title>
    <style>
        :root { --forest: #173f35; --sage: #e7eee5; --ivory: #faf9f5; --ink: #243d35; --muted: #63716b; --line: #e2e7df; }
        * { box-sizing: border-box; }
        body { margin: 0; font-family: 'Segoe UI', system-ui, sans-serif; background: var(--ivory); color: var(--ink); -webkit-font-smoothing: antialiased; }
        ::selection { background: #d5b77a; color: var(--forest); }
        a { color: inherit; text-decoration: none; }
        button, input { font: inherit; }
        a, button { -webkit-tap-highlight-color: transparent; touch-action: manipulation; }
        a:focus-visible, button:focus-visible, input:focus-visible { outline: 3px solid #9b782e; outline-offset: 4px; }
        svg { width: 20px; height: 20px; flex-shrink: 0; }
        .page { min-height: 100vh; min-height: 100svh; display: flex; flex-direction: column; }
        .container { width: min(100%, 1200px); margin-inline: auto; padding-inline: 24px; }
        .site-header { border-bottom: 1px solid var(--line); }
        .header-inner { min-height: 82px; display: flex; align-items: center; justify-content: space-between; gap: 20px; }
        .brand { display: inline-flex; align-items: center; gap: 12px; }
        .brand-icon { width: 40px; height: 40px; display: grid; place-items: center; border-radius: 12px; background: var(--forest); color: white; flex-shrink: 0; }
        .brand strong { display: block; color: var(--forest); font-size: 16px; line-height: 1.4; }
        .brand small { display: block; color: var(--muted); font-size: 12px; }
        .back-link { display: inline-flex; align-items: center; justify-content: center; gap: 9px; min-height: 44px; padding: 10px 15px; border: 1px solid #cfd9cd; border-radius: 10px; font-size: 13px; font-weight: 600; color: var(--forest); transition: background .2s; }
        .back-link:hover { background: var(--sage); }
        main { flex: 1; display: flex; align-items: center; padding-block: 44px; }
        .login-layout { display: grid; grid-template-columns: 1.1fr 1fr; align-items: center; gap: clamp(40px, 6vw, 88px); }
        .eyebrow { display: flex; align-items: center; gap: 9px; color: #366b58; font-size: 11px; font-weight: 750; letter-spacing: .13em; text-transform: uppercase; }
        .eyebrow::before { content: ''; width: 7px; height: 7px; border-radius: 50%; background: #366b58; flex-shrink: 0; }
        .welcome-title { margin: 18px 0 16px; color: var(--forest); font-size: clamp(36px, 3.6vw, 48px); font-weight: 750; line-height: 1.12; letter-spacing: -.045em; }
        .welcome-title em { color: #698367; font-family: Georgia, serif; font-weight: 400; }
        .welcome-description { max-width: 450px; margin: 0; color: var(--muted); font-size: 15px; line-height: 1.8; }
        .portrait { position: relative; height: 260px; margin-top: 26px; border-radius: 100px 100px 18px 18px; background: linear-gradient(155deg, #e2e9dc, #b4c7ab); overflow: hidden; isolation: isolate; }
        .portrait::before { content: ''; position: absolute; width: 220px; height: 220px; top: 24px; left: 50%; transform: translateX(-50%); border: 1px solid #f8fbefaa; border-radius: 50%; z-index: -1; }
        .portrait img { display: block; width: 100%; height: 100%; object-fit: contain; object-position: center bottom; }
        .portrait-caption { display: flex; align-items: center; gap: 12px; margin-top: 16px; }
        .caption-icon { display: grid; place-items: center; width: 38px; height: 38px; border-radius: 50%; color: #87672c; background: #f4ecd9; flex-shrink: 0; }
        .portrait-caption strong { display: block; font-size: 13px; font-weight: 650; }
        .portrait-caption small { display: block; margin-top: 3px; font-size: 12px; color: var(--muted); }
        .login-card { min-width: 0; padding: clamp(26px, 3.5vw, 42px); background: white; border: 1px solid var(--line); border-radius: 22px; box-shadow: 0 16px 48px #173f3508; }
        .portal-label { display: inline-flex; align-items: center; gap: 8px; padding: 8px 12px; border-radius: 8px; background: var(--sage); color: #366b58; font-size: 12px; font-weight: 600; }
        .portal-label svg { width: 16px; height: 16px; }
        .login-card h2 { margin: 22px 0 10px; font-size: 30px; line-height: 1.2; letter-spacing: -.035em; color: var(--forest); }
        .form-description { margin: 0 0 28px; color: var(--muted); font-size: 14px; line-height: 1.75; }
        .field { margin-bottom: 20px; }
        label { display: block; margin-bottom: 9px; font-size: 13px; font-weight: 650; }
        input { width: 100%; min-height: 50px; border: 1px solid #cfd9cd; border-radius: 10px; padding: 13px 15px; background: #fcfcfa; color: var(--ink); font-size: 16px; transition: border-color .2s, background .2s; }
        input::placeholder { color: #758078; }
        input:focus { border-color: #366b58; background: white; }
        input[aria-invalid="true"] { border-color: #b7433e; }
        .submit-button { display: flex; align-items: center; justify-content: center; gap: 12px; width: 100%; min-height: 50px; margin-top: 26px; padding: 14px 20px; border: 0; border-radius: 10px; background: var(--forest); color: white; font-size: 14px; font-weight: 650; cursor: pointer; transition: background .2s; }
        .submit-button:hover { background: #2d5b49; }
        .alert { margin-bottom: 22px; padding: 13px 15px; border: 1px solid #efc8c4; border-radius: 10px; background: #fff5f3; color: #943c35; font-size: 13px; line-height: 1.6; overflow-wrap: anywhere; }
        .demo-note { margin: 26px 0 0; padding-top: 22px; border-top: 1px solid var(--line); color: var(--muted); font-size: 12px; text-align: center; line-height: 1.8; }
        .demo-note code { padding: 3px 6px; border-radius: 4px; background: #f2f4ee; color: var(--ink); }
        .help-note { margin: 20px 0 0; color: var(--muted); font-size: 12px; line-height: 1.7; text-align: center; }
        .site-footer { padding-block: 20px; border-top: 1px solid var(--line); color: var(--muted); font-size: 12px; }
        .footer-inner { display: flex; justify-content: space-between; gap: 12px; flex-wrap: wrap; }
        @media (max-width: 900px) {
            .login-layout { gap: 32px; grid-template-columns: 1fr 1fr; }
            .welcome-title { font-size: 36px; }
            .login-card { padding: 28px; }
        }
        @media (max-width: 700px) {
            .header-inner { min-height: 74px; flex-wrap: wrap; padding-block: 16px; gap: 14px; }
            .brand strong { font-size: 14px; }
            .brand small { font-size: 11px; }
            .back-link { font-size: 12px; padding-inline: 12px; }
            main { padding-block: 32px; }
            .login-layout { max-width: 480px; grid-template-columns: 1fr; gap: 26px; }
            .welcome-title { font-size: 34px; margin-top: 14px; }
            .welcome-description { font-size: 14px; }
            .portrait, .portrait-caption { display: none; }
            .login-card h2 { font-size: 27px; }
            .footer-inner { justify-content: center; text-align: center; }
        }
        @media (max-width: 380px) {
            .container { padding-inline: 18px; }
            .login-card { padding: 24px 20px; }
            .back-link { width: 100%; }
        }
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { transition: none !important; }
        }
    </style>
</head>
<body>
    <div class="page">
        <header class="site-header">
            <div class="container header-inner">
                <a href="{{ route('landing') }}" class="brand" aria-label="SMA Smart School — Beranda">
                    <span class="brand-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m2 9 10-5 10 5-10 5L2 9Z"/><path d="M6 11v6c4 3 8 3 12 0v-6M22 9v7"/></svg>
                    </span>
                    <span><strong>SMA Smart School</strong><small>Sekolah Unggulan Terpadu</small></span>
                </a>
                <a href="{{ route('landing') }}" class="back-link">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m12 5-7 7 7 7M5 12h14"/></svg>
                    Kembali ke Beranda
                </a>
            </div>
        </header>
        <main>
            <div class="container login-layout">
                <section aria-labelledby="welcome-title">
                    <span class="eyebrow">Terhubung untuk bertumbuh</span>
                    <h1 class="welcome-title" id="welcome-title">Langkah kecil hari ini,<br><em>masa depan berarti.</em></h1>
                    <p class="welcome-description">Satu ruang untuk belajar, mendampingi, dan mengikuti setiap perkembangan. Bersama Smart School, setiap potensi punya tempat untuk tumbuh.</p>
                    <div class="portrait">
                        <img src="{{ asset('images/siswa.png') }}" alt="Siswa SMA Smart School" fetchpriority="high">
                    </div>
                    <div class="portrait-caption">
                        <span class="caption-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="8" r="5"/><path d="m8.5 12-2 9 5.5-3 5.5 3-2-9"/></svg>
                        </span>
                        <span><strong>Belajar. Berkarya. Berprestasi.</strong><small>Ruang untuk setiap potensi.</small></span>
                    </div>
                </section>
                <section class="login-card" aria-labelledby="login-title">
                    <span class="portal-label">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="5" y="10" width="14" height="11" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3M12 14v3"/></svg>
                        Portal Akademik
                    </span>
                    <h2 id="login-title">Selamat datang kembali.</h2>
                    <p class="form-description">Masuk dengan akun sekolah Anda untuk mengakses layanan Smart School.</p>
                    @if(session('error'))
                        <div class="alert" role="alert">{{ session('error') }}</div>
                    @endif
                    @if($errors->any())
                        <div class="alert" id="login-error" role="alert">{{ $errors->first() }}</div>
                    @endif
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="field">
                            <label for="email">Alamat email</label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                                @if($errors->has('email')) aria-invalid="true" aria-describedby="login-error" @endif
                                placeholder="email@smartschool.id">
                        </div>
                        <div class="field">
                            <label for="password">Password</label>
                            <input id="password" type="password" name="password" required autocomplete="current-password"
                                @if($errors->has('password')) aria-invalid="true" aria-describedby="login-error" @endif
                                placeholder="Masukkan password Anda">
                        </div>
                        <button type="submit" class="submit-button">
                            Masuk ke Sistem
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m12 5 7 7-7 7M5 12h14"/></svg>
                        </button>
                    </form>
                    <p class="demo-note">Password akun demo: <code>password</code></p>
                    <p class="help-note">Kesulitan masuk? Hubungi administrator sekolah untuk bantuan akses akun.</p>
                </section>
            </div>
        </main>
        <footer class="site-footer">
            <div class="container footer-inner">
                <span>&copy; {{ date('Y') }} SMA Smart School</span>
                <span>Belajar bersama. Tumbuh bersama.</span>
            </div>
        </footer>
    </div>
</body>
</html>
