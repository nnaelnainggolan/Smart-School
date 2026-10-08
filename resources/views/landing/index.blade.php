{{--
PANDUAN GAMBAR: simpan foto di public/images/landing/ dengan nama berikut.
Beranda tetap menggunakan public/images/siswa.png (PNG transparan, disarankan 900 x 1000).
Tentang: tentang-sekolah.jpg (gedung/lingkungan sekolah, 1200 x 900).
Program: program-ipa.jpg, program-ips.jpg, program-bahasa-tik.jpg (praktikum sains, diskusi sosial, pembelajaran bahasa/komputer; 1200 x 600).
Fasilitas: fasilitas-lab-ipa.jpg, fasilitas-lab-komputer.jpg, fasilitas-perpustakaan.jpg,
fasilitas-olahraga.jpg, fasilitas-kantin.jpg, fasilitas-musholla.jpg, fasilitas-wifi.jpg,
fasilitas-antar-jemput.jpg (foto fasilitas sesuai nama; 800 x 450).
PPDB: ppdb-siswa.jpg (kegiatan/penyambutan siswa, 1200 x 400).
Berita: gambar dari admin tetap diprioritaskan; berita-default.jpg menjadi cadangan.
Contoh berita kosong: berita-pengumuman.jpg, berita-prestasi.jpg, berita-kegiatan.jpg (1200 x 675).
Kontak: kontak-gerbang.jpg (gerbang dan akses masuk, 1200 x 400).
Testimoni: testimoni-1.jpg, testimoni-2.jpg, testimoni-3.jpg (potret narasumber sesuai identitas; 300 x 300).
Gunakan foto asli sekolah. Foto landscape akan terpotong proporsional (object-fit: cover).
Atur object-position pada .slot-photo jika posisi subjek perlu disesuaikan.
Jika foto belum tersedia, ikon/placeholder tetap tampil. Hindari teks penting di tepi foto.
--}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMA Smart School — Sekolah Unggulan Terpadu</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: { extend: { colors: { primary: '#173F35', secondary: '#366B58', accent: '#D5B77A' } } }
        }
    </script>
    <style>
:root{--forest:#173f35;--sage:#e7eee5;--ivory:#faf9f5;--ink:#243d35;--muted:#63716b;--line:#e2e7df}
*{box-sizing:border-box}html{scroll-behavior:smooth;scroll-padding-top:100px}body{margin:0;font-family:'Segoe UI',system-ui,sans-serif;background:var(--ivory);color:var(--ink);-webkit-font-smoothing:antialiased}::selection{background:#d5b77a;color:#173f35}
[x-cloak]{display:none!important}a,button,input,select{ -webkit-tap-highlight-color:transparent}a,button{transition:background .2s,color .2s,transform .2s}a:focus-visible,button:focus-visible,input:focus-visible,select:focus-visible{outline:3px solid #9b782e;outline-offset:4px}button,a{touch-action:manipulation}section{scroll-margin-top:88px}section.py-20{padding-top:96px;padding-bottom:96px}.max-w-7xl{max-width:1200px}h1,h2,h3{letter-spacing:-.035em}h2.text-4xl{font-size:clamp(1.9rem,3.3vw,2.65rem);font-weight:750;line-height:1.2}.font-black{font-weight:750}.text-gray-800{color:var(--ink)}.text-gray-500,.text-gray-600{color:var(--muted)}.text-gray-400{color:#68756e}.bg-slate-50,.bg-gray-50{background:#f2f4ee}.border-gray-100{border-color:var(--line)}.shadow-sm,.shadow-md,.shadow-xl,.shadow-2xl,.shadow-lg{box-shadow:0 8px 30px #173f3507}.rounded-3xl{border-radius:22px}.gradient-text{color:#366b58;background:none;-webkit-text-fill-color:currentColor}
nav{background:#faf9f5f2!important;box-shadow:none!important;border-color:var(--line)!important}nav>.max-w-7xl{min-height:82px}nav .gap-8{gap:24px}.nav-link{position:relative;font-size:13px}.nav-link:hover,.nav-link.is-active{color:var(--forest);font-weight:700}.nav-link.is-active:after{content:'';position:absolute;bottom:-11px;left:0;right:0;height:2px;background:var(--forest)}nav .border-accent{border-color:#d7dfd4;color:var(--forest);border-width:1px}nav .border-accent:hover{background:var(--sage);color:var(--forest)}
.hero{padding:144px 0 60px;background:var(--ivory);overflow:hidden}.hero-grid{display:grid;grid-template-columns:1.06fr 1fr;gap:66px;align-items:center}.eyebrow{display:inline-flex;align-items:center;gap:9px;font-size:11px;font-weight:750;letter-spacing:.13em;text-transform:uppercase;color:#366b58}.eyebrow:before{content:'';width:7px;height:7px;border-radius:50%;background:#366b58}.hero h1{font-size:clamp(2.7rem,4.7vw,4.35rem);line-height:1.1;font-weight:750;margin:25px 0 24px;color:var(--forest);letter-spacing:-.052em}.hero h1 em{font-family:Georgia,serif;font-weight:400;color:#698367}.hero-description{font-size:16px;line-height:1.85;color:var(--muted);max-width:475px}.hero-actions{display:flex;gap:12px;flex-wrap:wrap;margin-top:30px}.button-primary,.button-outline{min-height:48px;display:inline-flex;align-items:center;justify-content:center;gap:12px;padding:13px 21px;border-radius:10px;font-size:13px;font-weight:650}.button-primary{background:var(--forest);color:white}.button-primary:hover{background:#2d5b49;transform:translateY(-2px)}.button-outline{border:1px solid #cfd9cd;color:var(--forest)}.button-outline:hover{background:var(--sage)}.hero-stats{display:grid;grid-template-columns:repeat(3,1fr);gap:20px;border-top:1px solid var(--line);margin-top:38px;padding-top:25px}.hero-stats strong{font-size:27px;font-weight:700;letter-spacing:-.03em}.hero-stats span{display:block;font-size:11px;color:var(--muted);margin-top:4px}.hero-art{position:relative;padding-bottom:24px}.portrait-frame{height:490px;border-radius:160px 160px 24px 24px;background:linear-gradient(155deg,#e2e9dc,#b4c7ab);position:relative;overflow:hidden;isolation:isolate;display:flex;align-items:flex-end;justify-content:center}.portrait-frame:before{content:'';position:absolute;width:360px;height:360px;border:1px solid #f8fbefaa;border-radius:50%;top:50px;left:50%;transform:translateX(-50%);z-index:-1}.portrait-frame:after{content:'SMART SCHOOL';position:absolute;font-weight:750;letter-spacing:.24em;font-size:11px;left:0;right:0;text-align:center;top:29px;color:#3c604c}.portrait-frame img{position:relative;width:100%;height:94%;object-fit:contain;object-position:bottom;z-index:1}.portrait-fallback{position:absolute;inset:100px 20px 60px;display:flex;flex-direction:column;align-items:center;justify-content:center;color:#48644e;gap:20px;text-align:center;font-family:Georgia,serif;font-size:32px}.portrait-fallback i{font-size:76px}.hero-note{position:absolute;bottom:0;left:-24px;background:#fff;padding:18px 22px;border:1px solid var(--line);border-radius:14px;box-shadow:0 15px 35px #173f3510;display:flex;align-items:center;gap:14px;z-index:2}.hero-note i{width:42px;height:42px;display:grid;place-items:center;background:#f4ecd9;border-radius:50%;color:#87672c}.hero-note strong{display:block;font-size:13px}.hero-note small{font-size:11px;color:var(--muted)}.hero-caption{display:flex;justify-content:flex-end;font-size:10px;letter-spacing:.12em;text-transform:uppercase;color:var(--muted);padding:14px 2px}.hero-bottom{border-top:1px solid var(--line);margin-top:52px;padding-top:22px;display:flex;gap:20px;justify-content:space-between;color:var(--muted);font-size:12px}.hero-bottom span{display:flex;align-items:center;gap:9px}.hero-bottom i{color:#52775c}
.card-hover{transition:transform .25s,box-shadow .25s,border-color .25s}.card-hover:hover{transform:translateY(-4px);box-shadow:0 15px 35px #173f350c;border-color:#bccbbb}.pattern-bg{background-image:radial-gradient(#ffffff13 1px,transparent 1px);background-size:22px 22px}#tentang .aspect-\[4\/3\]{background:linear-gradient(140deg,#244e40,#517459)}#tentang .absolute.-right-6{right:16px;bottom:-20px}.text-blue-100,.text-blue-200,.text-blue-300{color:#d8e5d5}#tentang .grid-cols-2>div>div:first-child,#fasilitas .w-14{background:#eaf0e5;color:#416a4f}#program .h-36{height:112px;background:#e7eee3;justify-content:flex-start;padding-left:28px}#program .h-36>i{font-size:35px;color:#3d644e}#program .card-hover:nth-child(2) .h-36{background:#eeeadf}#program .card-hover:nth-child(2) .h-36>i{color:#80683c}#program .card-hover:nth-child(3) .h-36{background:#e6eceb}#program .card-hover:nth-child(3) .h-36>i{color:#4c6b66}#program .h-36>span{background:#fff9;color:#425b46;font-size:10px}#program .p-6{padding:26px}#program h3{font-size:22px}#program .card-hover{display:flex;flex-direction:column}#program .p-6{flex:1;display:flex;flex-direction:column}#program .p-6>.flex{margin-top:auto;padding-top:8px}#fasilitas .card-hover{text-align:left;padding:24px}#fasilitas .w-14{margin-left:0;width:46px;height:46px}#fasilitas p{line-height:1.7}#ppdb{background:#173f35}#ppdb .bg-white.rounded-3xl{padding:34px;color:var(--ink)}input,select{min-height:46px}#berita .h-44{height:205px;background:linear-gradient(135deg,#426b56,#7c977b)}#berita .p-5{padding:25px}#kontak .bg-gray-50{background:#f5f6f1;border:1px solid var(--line)}#kontak .w-12{background:#e6ede1;color:#41694f}#kontak .w-12 i{color:inherit}#kontak .bg-green-500{background:#366b58}footer{background:#12372e!important}footer .text-accent{color:#d5b77a}.bg-accent.text-white{color:#173f35}.text-accent{color:#896c34}#ppdb .text-accent,footer .text-accent{color:#dfc38b}footer .bg-accent:hover{background:#e6c990}section>.max-w-7xl>.text-center>span{background:transparent;color:#527259;padding:0;letter-spacing:.14em;text-transform:uppercase;font-size:11px}section>.max-w-7xl>.text-center>p{font-size:14px;line-height:1.8}#tentang{border-top:1px solid var(--line)}.skip-link{position:fixed;top:-100px;left:20px;z-index:100;background:#173f35;color:white;padding:12px 20px}.skip-link:focus{top:12px}
@media(min-width:1024px) and (max-width:1150px){nav .gap-8{gap:15px}nav>.max-w-7xl{padding-left:20px;padding-right:20px}}
@media(max-width:1023px){.hero-grid{gap:32px}.hero{padding-top:125px}.portrait-frame{height:410px}.hero h1{font-size:3.1rem}.hero-note{left:-10px;padding:14px}.hero-bottom{flex-wrap:wrap}nav>.max-w-7xl{min-height:74px}}
@media(max-width:767px){section.py-20{padding-top:64px;padding-bottom:64px}.hero{padding-top:110px;padding-bottom:40px}.hero-grid{grid-template-columns:1fr;gap:36px}.hero h1{font-size:clamp(2.65rem,10vw,3.6rem)}.hero-description{font-size:15px}.hero-art{max-width:420px;width:100%;margin:0 auto}.portrait-frame{height:390px;border-radius:130px 130px 20px 20px}.hero-note{left:0}.hero-bottom{margin-top:30px;gap:16px;font-size:11px}.hero-stats{gap:12px}.hero-stats strong{font-size:25px}#tentang .gap-16{gap:52px}#ppdb .bg-white.rounded-3xl{padding:24px}#ppdb form .grid-cols-2{grid-template-columns:1fr}#fasilitas .card-hover{padding:18px}#kontak .min-h-\[450px\]{min-height:340px}#kontak iframe{min-height:320px}nav .text-base{font-size:14px}}
@media(prefers-reduced-motion:reduce){html{scroll-behavior:auto}*,*:before,*:after{animation:none!important;transition:none!important}.card-hover:hover{transform:none}}

/* Continuous landing page: navbar offset and clear section boundaries. */
:root{--nav-height:82px}
html{scroll-padding-top:var(--nav-height)}
#main-content>section{scroll-margin-top:0}
#main-content>section[id]{min-height:calc(100svh - var(--nav-height));padding:56px 0;display:flex;align-items:center;border-top:1px solid var(--line)}
#main-content>section[id]>.max-w-7xl{width:100%}
#main-content>#beranda{min-height:100svh;padding-top:calc(var(--nav-height) + 40px);padding-bottom:40px;border-top:0}
#main-content>section[data-page-extra]{border-top:1px solid var(--line)}
@media(max-width:1023px){:root{--nav-height:74px}}
@media(max-width:767px){#main-content>section[id]{padding:36px 0;align-items:flex-start}#main-content>#beranda{padding-top:calc(var(--nav-height) + 32px)}}

/* Compact hero: statistics belong to the student portrait column. */
#beranda .hero-art{padding-bottom:0;width:100%}
#beranda .hero-note{position:static;margin-top:10px;padding:10px 14px;box-shadow:none;gap:10px}
#beranda .hero-note i{width:32px;height:32px;flex-shrink:0}
#beranda .hero-note strong{font-size:12px}
#beranda .hero-stats{margin-top:12px;padding-top:12px;gap:12px;text-align:center}
#beranda .hero-stats strong{font-size:25px}
#beranda .hero-stats span{font-size:11px}
@media(min-width:1024px){
 #main-content>#beranda{min-height:100svh;padding-top:calc(var(--nav-height) + 20px);padding-bottom:20px}
 #beranda .hero-grid{gap:48px;grid-template-columns:1.08fr 1fr}
 #beranda .portrait-frame{height:clamp(220px,calc(100svh - 350px),420px);border-radius:100px 100px 20px 20px}
 #beranda .portrait-frame img{height:94%;width:100%;object-fit:contain}
 #beranda .portrait-frame:before{width:250px;height:250px;top:30px}
 #beranda .portrait-frame:after{top:17px;font-size:9px}
 #beranda h1{font-size:clamp(46px,4.6vw,64px);line-height:1.08;margin:18px 0 22px}
 #beranda .hero-description{font-size:17px;line-height:1.7;max-width:540px}
 #beranda .hero-actions{margin-top:22px}
 #beranda .hero-bottom{margin-top:20px;padding-top:14px;font-size:11px}
}
@media(min-width:1024px) and (max-height:650px){
 #beranda h1{font-size:clamp(46px,4.4vw,60px);margin:14px 0 18px}
 #beranda .eyebrow{font-size:10px;letter-spacing:.08em}
 #beranda .hero-description{font-size:16px;line-height:1.65}
 #beranda .hero-actions{margin-top:18px}
 #beranda .hero-stats{margin-top:8px;padding-top:8px}
 #beranda .hero-stats strong{font-size:22px}
 #beranda .hero-bottom{margin-top:14px;padding-top:12px}
}
@media(max-width:1023px){#beranda .hero-stats{margin-bottom:4px}#beranda .portrait-frame{height:340px}}
@media(max-width:767px){#beranda .portrait-frame{height:300px}#beranda .hero-stats strong{font-size:23px}}

/* Laptop layout: keep each section within the space below the navigation. */
@media(min-width:1024px){
 #main-content>section[id]:not(#beranda){min-height:calc(100svh - var(--nav-height));padding:22px 0}
 #main-content>section[id]:not(#beranda) h2{font-size:30px;line-height:1.15;margin-bottom:12px}
 #main-content>section[id]>.max-w-7xl>.text-center,
 #berita>.max-w-7xl>.flex{margin-bottom:24px}
 #main-content>section[id]>.max-w-7xl>.text-center>p{font-size:13px;line-height:1.6;max-width:700px;margin-left:auto;margin-right:auto}
 #main-content>section[id]>.max-w-7xl>.text-center>span{margin-bottom:10px}
 #tentang .gap-16{gap:36px}
 #tentang .aspect-\[4\/3\]{aspect-ratio:auto;height:330px}
 #tentang .absolute.-right-6{bottom:12px;padding:12px}
 #tentang .grid>div>span{margin-bottom:10px;font-size:12px}
 #tentang .grid>div>p{font-size:13px;line-height:1.65;margin-bottom:12px}
 #tentang .grid-cols-2{gap:10px;margin-bottom:14px}
 #tentang .grid-cols-2>.p-4{padding:10px;gap:9px;align-items:center}
 #tentang .grid-cols-2 .w-10{width:32px;height:32px}
 #tentang .grid-cols-2 p.text-sm{font-size:12px}
 #tentang .grid>div>a{padding:10px 18px;font-size:13px}
 #program .h-36{height:80px}
 #program .h-36>i{font-size:30px}
 #program .p-6{padding:18px}
 #program h3{font-size:20px}
 #program .p-6>p{font-size:13px;line-height:1.65;margin-bottom:12px}
 #program .gap-6,#berita .gap-6{gap:18px}
 #fasilitas .gap-5{gap:14px}
 #fasilitas .card-hover{padding:16px}
 #fasilitas .w-14{width:36px;height:36px;margin-bottom:8px}
 #fasilitas .w-14 i{font-size:19px}
 #fasilitas p{line-height:1.5}
 #berita .h-44{height:132px}
 #berita .p-5{padding:16px}
 #berita .p-5>span{margin-bottom:9px}
 #berita .p-5>p{font-size:13px;line-height:1.6;margin-bottom:12px}
 #berita h3{font-size:15px}
 #kontak>.max-w-7xl>.grid{gap:24px;align-items:stretch;grid-template-columns:1.15fr 1fr}
 #kontak .space-y-5{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:12px}
 #kontak .space-y-5>div{margin:0;padding:14px;gap:10px;min-width:0}
 #kontak .space-y-5>div:last-child{grid-column:1/-1;display:flex;align-items:center;justify-content:space-between}
 #kontak .space-y-5>div:last-child h4{margin:0}
 #kontak .w-12{width:32px;height:32px}
 #kontak .w-12 i{font-size:15px}
 #kontak .space-y-5 p,#kontak .space-y-5 h4{font-size:12px;line-height:1.6;overflow-wrap:anywhere}
 #kontak .space-y-5 .w-10{width:30px;height:30px}
 #kontak .space-y-5 a.inline-flex{padding:7px 10px;font-size:11px}
 #kontak .min-h-\[450px\]{min-height:0;height:100%}
 #kontak iframe{min-height:0;height:260px;flex:1}
 #ppdb .gap-10{gap:36px}
 #ppdb .bg-white.rounded-3xl{padding:22px}
 #ppdb .text-white>.inline-flex{margin-bottom:12px;padding:6px 12px}
 #ppdb .text-white>p{font-size:13px;line-height:1.6;margin-bottom:16px}
 #ppdb .text-white>.grid{gap:10px;margin-bottom:0}
 #ppdb .text-white>.grid>div{padding:12px}
 #ppdb .bg-white.rounded-3xl>p{margin-bottom:14px;font-size:12px}
 #ppdb form.space-y-4> :not([hidden])~ :not([hidden]){margin-top:10px}
 #ppdb label{font-size:12px;margin-bottom:4px}
 #ppdb input,#ppdb select{min-height:38px;padding:8px 12px;font-size:13px}
 #ppdb button[type=submit]{padding:10px;font-size:13px}
 #ppdb #ppdb-success{padding:10px;margin-top:10px}
 #ppdb #ppdb-success i{display:none}
 #main-content>section[data-page-extra]{min-height:calc(100svh - var(--nav-height));padding:32px 0;display:flex;align-items:center}
 #main-content>section[data-page-extra]>.max-w-7xl{width:100%}
 #main-content>section[data-page-extra] .mb-12{margin-bottom:24px}
}
.news-pagination{display:flex;justify-content:center;align-items:center;gap:14px;margin-top:16px;font-size:12px}
.news-pagination[hidden],#berita .card-hover[hidden]{display:none!important}
.news-pagination button{border:1px solid var(--line);border-radius:8px;padding:7px 12px;background:white;color:var(--forest)}
.news-pagination button:disabled{opacity:.4;cursor:default}

/* IMAGE SLOTS — photos replace placeholders without changing their dimensions. */
.photo-slot,.about-photo,.facility-photo,.news-photo,.avatar-photo{position:relative;overflow:hidden;isolation:isolate}
.slot-photo{position:absolute;inset:0;display:block;width:100%;height:100%;object-fit:cover;object-position:center;z-index:1}
.photo-slot{background:#e5ede1;border-radius:12px;display:grid;place-items:center}
.slot-label{display:flex;gap:8px;align-items:center;justify-content:center;padding:12px;text-align:center;font-size:12px;color:#496550}
.about-photo>.text-center{position:relative;z-index:2;background:linear-gradient(0deg,#173f35e8,#173f35a6);width:100%;align-self:flex-end;padding:18px}
.about-photo>.text-center>.w-24{display:none}
.about-photo>.text-center>.grid{margin-bottom:0!important}
#program .h-36{overflow:hidden;isolation:isolate}
#program .h-36>span{z-index:2;background:#faf9f5eb;color:#173f35}
#fasilitas .facility-photo{width:100%;height:94px;margin-bottom:10px;border-radius:10px}
.admission-photo{height:100px;margin-bottom:14px}
.contact-photo{height:90px;flex-shrink:0;border-radius:0}
.avatar-photo{flex-shrink:0}
@media(min-width:1024px){
 #program .h-36{height:110px}
 #fasilitas .card-hover{padding:12px}
 #fasilitas .facility-photo{width:100%;height:72px;margin-bottom:8px}
 #ppdb .admission-photo{height:84px;margin-bottom:10px}
 #ppdb .text-white>.inline-flex{margin-bottom:8px}
 #ppdb .text-white>.grid>div{padding:8px 12px}
 #ppdb .text-white>.grid>div>i{display:inline-block;margin-right:7px;margin-bottom:0}
 #kontak iframe{height:180px}
}
</style>
</head>
<body>
<a class="skip-link" href="#main-content">Lewati ke konten utama</a>
<!-- ===== NAVBAR ===== -->
<nav class="fixed top-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-md shadow-sm border-b border-gray-100" x-data="{ open: false }" @keydown.escape.window="open = false" aria-label="Navigasi utama">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <div class="max-w-7xl mx-auto px-6 py-3.5 flex items-center justify-between">
        <!-- Logo -->
        <a href="#beranda" class="flex items-center gap-3">
            <div class="w-10 h-10 bg-primary rounded-xl flex items-center justify-center shadow-md">
                <i class="fa-solid fa-graduation-cap text-white text-base"></i>
            </div>
            <div>
                <p class="font-bold text-primary text-base leading-tight">SMA Smart School</p>
                <p class="text-xs text-gray-400 leading-tight">Sekolah Unggulan Terpadu</p>
            </div>
        </a>
        <!-- Desktop Nav -->
        <div class="hidden lg:flex items-center gap-8">
            <a href="#beranda" class="nav-link text-sm font-medium text-gray-600">Beranda</a>
            <a href="#tentang" class="nav-link text-sm font-medium text-gray-600">Tentang</a>
            <a href="#program" class="nav-link text-sm font-medium text-gray-600">Program</a>
            <a href="#fasilitas" class="nav-link text-sm font-medium text-gray-600">Fasilitas</a>
            <a href="#berita" class="nav-link text-sm font-medium text-gray-600">Berita</a>
            <a href="#kontak" class="nav-link text-sm font-medium text-gray-600">Kontak</a>
        </div>
        <div class="hidden lg:flex items-center gap-3">
            <a href="#ppdb" class="px-4 py-2 border-2 border-accent text-accent rounded-xl text-sm font-bold hover:bg-accent hover:text-white transition">Daftar PPDB</a>
            <a href="{{ route('login') }}" class="px-4 py-2 bg-primary text-white rounded-xl text-sm font-bold hover:bg-secondary transition shadow-md">
                <i class="fa-solid fa-right-to-bracket mr-1.5"></i>Login
            </a>
        </div>
        <!-- Mobile Toggle -->
        <button type="button" aria-label="Buka atau tutup menu" aria-controls="mobile-menu" :aria-expanded="open.toString()" @click="open = !open" class="lg:hidden p-2 text-gray-600">
            <i :class="open ? 'fa-solid fa-xmark' : 'fa-solid fa-bars'" class="text-xl"></i>
        </button>
    </div>
    <!-- Mobile Menu -->
    <div id="mobile-menu" x-show="open" x-cloak @click="if ($event.target.closest('a')) open = false" class="lg:hidden bg-white border-t border-gray-100 px-6 py-4 space-y-3">
        <a href="#beranda" class="block text-sm font-medium text-gray-600 py-2">Beranda</a>
        <a href="#tentang" class="block text-sm font-medium text-gray-600 py-2">Tentang</a>
        <a href="#program" class="block text-sm font-medium text-gray-600 py-2">Program</a>
        <a href="#fasilitas" class="block text-sm font-medium text-gray-600 py-2">Fasilitas</a>
        <a href="#berita" class="block text-sm font-medium text-gray-600 py-2">Berita</a>
        <a href="#kontak" class="block text-sm font-medium text-gray-600 py-2">Kontak</a>
        <div class="flex gap-2 pt-2 border-t border-gray-100">
            <a href="#ppdb" class="flex-1 text-center px-4 py-2 border-2 border-accent text-accent rounded-xl text-sm font-bold">Daftar PPDB</a>
            <a href="{{ route('login') }}" class="flex-1 text-center px-4 py-2 bg-primary text-white rounded-xl text-sm font-bold">Login</a>
        </div>
    </div>
</nav>
<main id="main-content">
<section id="beranda" class="hero">
  <div class="max-w-7xl mx-auto px-6">
    <div class="hero-grid">
      <div>
        <span class="eyebrow">Sekolah unggulan terpadu · Medan</span>
        <h1>Tempat bertumbuh,<br>menjadi generasi Baru<br><em>SMK HASANUDDIN</em></h1>
        <p class="hero-description">Pendidikan yang menginspirasi, lingkungan yang mendukung. Bersama SMK Smart School, bangun masa depan yang cerdas, berkarakter, dan penuh kemungkinan.</p>
        <div class="hero-actions">
          <a href="#ppdb" class="button-primary">Informasi PPDB <i class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true"></i></a>
          <a href="#tentang" class="button-outline">Kenali sekolah kami <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
        </div>
      </div>
      <div class="hero-art">
        <div class="portrait-frame">
          <div class="portrait-fallback" style="display:none"><i class="fa-solid fa-school" aria-hidden="true"></i><span>Belajar hari ini.<br>Bersinar esok hari.</span></div>
          <img src="{{ asset('images/siswa.png') }}" alt="Siswa SMA Smart School" fetchpriority="high" onerror="this.style.display='none';this.previousElementSibling.style.display='flex'">
        </div>
        <div class="hero-note"><i class="fa-solid fa-award" aria-hidden="true"></i><div><strong>Belajar. Berkarya. Berprestasi.</strong><small>Ruang untuk setiap potensi.</small></div></div>
        <div class="hero-stats"><div><strong>15+</strong><span>Tahun mendidik</span></div><div><strong>1.200+</strong><span>Siswa aktif</span></div><div><strong>98%</strong><span>Kelulusan</span></div></div>
      </div>
    </div>
    <div class="hero-bottom"><span><i class="fa-solid fa-graduation-cap" aria-hidden="true"></i> Kurikulum modern</span><span><i class="fa-solid fa-heart" aria-hidden="true"></i> Pendidikan karakter</span><span><i class="fa-solid fa-laptop" aria-hidden="true"></i> Terintegrasi digital</span><span><i class="fa-solid fa-seedling" aria-hidden="true"></i> Lingkungan suportif</span></div>
  </div>
</section>
<!-- ===== TENTANG ===== -->
<section id="tentang" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <!-- Left: Image placeholder + stats -->
            <div class="relative">
                <div class="about-photo bg-gradient-to-br from-primary to-secondary rounded-3xl overflow-hidden aspect-[4/3] flex items-center justify-center shadow-2xl">
                    <img class="slot-photo" src="{{ asset('images/landing/tentang-sekolah.jpg') }}" alt="Gedung dan lingkungan sekolah" loading="lazy" decoding="async" onerror="this.style.display='none'">
                    <!-- Identitas sekolah tetap tersedia sebagai keterangan foto -->
                    <div class="text-center text-white p-8">
                        <div class="w-24 h-24 bg-white/20 rounded-3xl flex items-center justify-center mx-auto mb-4">
                            <i class="fa-solid fa-school text-5xl text-white"></i>
                        </div>
                        <p class="font-bold text-xl">SMK Smart School</p>
                        <p class="text-blue-200 text-sm mt-1">Jl. Pendidikan No. 1, Medan</p>
                        <div class="mt-4 grid grid-cols-2 gap-3">
                            <div class="bg-white/10 rounded-xl p-3">
                                <i class="fa-solid fa-trophy text-accent text-xl block mb-1"></i>
                                <p class="text-xs text-blue-100">50+ Prestasi</p>
                            </div>
                            <div class="bg-white/10 rounded-xl p-3">
                                <i class="fa-solid fa-certificate text-accent text-xl block mb-1"></i>
                                <p class="text-xs text-blue-100">Akreditasi A</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Floating badge -->
                <div class="absolute -bottom-6 -right-6 bg-white rounded-2xl p-5 shadow-2xl border border-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                            <i class="fa-solid fa-users text-green-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-2xl font-black text-gray-800">1200+</p>
                            <p class="text-xs text-gray-500">Siswa Aktif</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Right: Content -->
            <div>
                <span class="inline-block px-4 py-1.5 bg-secondary/10 text-secondary rounded-full text-sm font-bold mb-4">Tentang Kami</span>
                <h2 class="text-4xl font-black text-gray-800 leading-tight mb-5">
                    Membentuk Generasi <span class="gradient-text">Cerdas & Berkarakter</span>
                </h2>
                <p class="text-gray-600 leading-relaxed mb-5">
                    SMK Smart School adalah sekolah menengah atas unggulan yang berdiri sejak tahun 2009 di kota Medan. Kami berkomitmen untuk memberikan pendidikan berkualitas tinggi dengan menggabungkan kurikulum nasional dan pengembangan karakter.
                </p>
                <p class="text-gray-600 leading-relaxed mb-8">
                    Dengan dukungan teknologi digital melalui <strong>Sistem Informasi Smart School</strong>, orang tua dapat memantau perkembangan akademik anak secara real-time, mulai dari nilai, absensi, hingga layanan konseling online.
                </p>
                <div class="grid grid-cols-2 gap-4 mb-8">
                    @foreach([
                        ['icon'=>'fa-graduation-cap','color'=>'bg-blue-100 text-blue-600','title'=>'Kurikulum Modern','desc'=>'Merdeka Belajar & STEM'],
                        ['icon'=>'fa-heart','color'=>'bg-red-100 text-red-600','title'=>'Pendidikan Karakter','desc'=>'Berbasis nilai & akhlak'],
                        ['icon'=>'fa-laptop','color'=>'bg-purple-100 text-purple-600','title'=>'Teknologi Digital','desc'=>'Pembelajaran berbasis IT'],
                        ['icon'=>'fa-award','color'=>'bg-orange-100 text-orange-600','title'=>'Prestasi Nasional','desc'=>'Olimpiade & lomba'],
                    ] as $item)
                    <div class="flex items-start gap-3 p-4 bg-gray-50 rounded-2xl">
                        <div class="w-10 h-10 {{ $item['color'] }} rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid {{ $item['icon'] }}"></i>
                        </div>
                        <div>
                            <p class="font-bold text-gray-800 text-sm">{{ $item['title'] }}</p>
                            <p class="text-xs text-gray-500">{{ $item['desc'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
                <a href="#kontak" class="inline-flex items-center gap-2 px-6 py-3 bg-primary text-white rounded-xl font-bold hover:bg-secondary transition shadow-lg">
                    <i class="fa-solid fa-phone"></i> Hubungi Kami
                </a>
            </div>
        </div>
    </div>
</section>
<!-- ===== PROGRAM / JURUSAN ===== -->
<section id="program" class="py-20 bg-slate-50">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-14">
            <span class="inline-block px-4 py-1.5 bg-secondary/10 text-secondary rounded-full text-sm font-bold mb-3">Program Studi</span>
            <h2 class="text-4xl font-black text-gray-800 mb-4">Program Unggulan Kami</h2>
            <p class="text-gray-500 max-w-xl mx-auto">Kami menyediakan program jurusan yang komprehensif untuk mempersiapkan siswa menghadapi tantangan masa depan</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach([
                ['icon'=>'fa-flask','color'=>'from-blue-500 to-blue-700','image'=>'program-ipa.jpg','title'=>'IPA (MIPA)','desc'=>'Program unggulan sains dan matematika dengan laboratorium modern dan guru berpengalaman','tags'=>['Matematika','Fisika','Kimia','Biologi'],'badge'=>'Paling Diminati'],
                ['icon'=>'fa-chart-line','color'=>'from-green-500 to-green-700','image'=>'program-ips.jpg','title'=>'IPS','desc'=>'Program ilmu sosial yang membangun pemahaman mendalam tentang ekonomi, geografi dan sejarah','tags'=>['Ekonomi','Geografi','Sosiologi','Sejarah'],'badge'=>null],
                ['icon'=>'fa-code','color'=>'from-purple-500 to-purple-700','image'=>'program-bahasa-tik.jpg','title'=>'Bahasa & TIK','desc'=>'Program bahasa dan teknologi informasi untuk mempersiapkan generasi digital yang komunikatif','tags'=>['Bahasa Inggris','Bahasa Arab','Komputer','Multimedia'],'badge'=>'Baru'],
            ] as $p)
            <div class="card-hover bg-white rounded-3xl overflow-hidden shadow-md border border-gray-100">
                <div class="h-36 bg-gradient-to-br {{ $p['color'] }} flex items-center justify-center relative">
                    <i class="fa-solid {{ $p['icon'] }} text-6xl text-white/80"></i><img class="slot-photo" src="{{ asset('images/landing/' . $p['image']) }}" alt="{{ $p['title'] }}" loading="lazy" decoding="async" onerror="this.style.display='none'">
                    @if($p['badge'])
                    <span class="absolute top-4 right-4 bg-accent text-white text-xs font-bold px-2.5 py-1 rounded-full">{{ $p['badge'] }}</span>
                    @endif
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-black text-gray-800 mb-2">{{ $p['title'] }}</h3>
                    <p class="text-gray-500 text-sm leading-relaxed mb-4">{{ $p['desc'] }}</p>
                    <div class="flex flex-wrap gap-1.5">
                        @foreach($p['tags'] as $tag)
                        <span class="px-2.5 py-1 bg-gray-100 text-gray-600 text-xs font-medium rounded-full">{{ $tag }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
<!-- ===== FASILITAS ===== -->
<section id="fasilitas" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-14">
            <span class="inline-block px-4 py-1.5 bg-accent/10 text-accent rounded-full text-sm font-bold mb-3">Fasilitas</span>
            <h2 class="text-4xl font-black text-gray-800 mb-4">Fasilitas Lengkap & Modern</h2>
            <p class="text-gray-500 max-w-xl mx-auto">Didukung sarana prasarana terbaik untuk menunjang kegiatan belajar mengajar yang optimal</p>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
            @foreach([
                ['icon'=>'fa-microscope','color'=>'bg-blue-50 text-blue-600','image'=>'fasilitas-lab-ipa.jpg','title'=>'Lab IPA Lengkap','desc'=>'3 laboratorium fisika, kimia & biologi'],
                ['icon'=>'fa-computer','color'=>'bg-purple-50 text-purple-600','image'=>'fasilitas-lab-komputer.jpg','title'=>'Lab Komputer','desc'=>'50 unit PC dengan internet cepat'],
                ['icon'=>'fa-book-open','color'=>'bg-green-50 text-green-600','image'=>'fasilitas-perpustakaan.jpg','title'=>'Perpustakaan','desc'=>'5000+ koleksi buku & e-library'],
                ['icon'=>'fa-futbol','color'=>'bg-orange-50 text-orange-600','image'=>'fasilitas-olahraga.jpg','title'=>'Lapangan Olahraga','desc'=>'Lapangan basket, futsal & voli'],
                ['icon'=>'fa-utensils','color'=>'bg-red-50 text-red-600','image'=>'fasilitas-kantin.jpg','title'=>'Kantin Sehat','desc'=>'Menu bergizi & terjangkau'],
                ['icon'=>'fa-mosque','color'=>'bg-teal-50 text-teal-600','image'=>'fasilitas-musholla.jpg','title'=>'Musholla','desc'=>'Tempat ibadah yang nyaman'],
                ['icon'=>'fa-wifi','color'=>'bg-indigo-50 text-indigo-600','image'=>'fasilitas-wifi.jpg','title'=>'WiFi Campus','desc'=>'Internet berkecepatan tinggi'],
                ['icon'=>'fa-bus','color'=>'bg-yellow-50 text-yellow-600','image'=>'fasilitas-antar-jemput.jpg','title'=>'Antar Jemput','desc'=>'Layanan transportasi siswa'],
            ] as $f)
            <div class="card-hover p-5 rounded-2xl border border-gray-100 text-center bg-white shadow-sm">
                <div class="facility-photo w-14 h-14 {{ $f['color'] }} rounded-2xl flex items-center justify-center mx-auto mb-3">
                    <i class="fa-solid {{ $f['icon'] }} text-2xl"></i><img class="slot-photo" src="{{ asset('images/landing/' . $f['image']) }}" alt="{{ $f['title'] }}" loading="lazy" decoding="async" onerror="this.style.display='none'">
                </div>
                <h4 class="font-bold text-gray-800 text-sm mb-1">{{ $f['title'] }}</h4>
                <p class="text-xs text-gray-500">{{ $f['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>
<!-- ===== PPDB BANNER ===== -->
<section id="ppdb" class="py-20 bg-gradient-to-r from-primary to-secondary relative overflow-hidden">
    <div class="absolute inset-0 pattern-bg opacity-30"></div>
    <div class="max-w-7xl mx-auto px-6 relative">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">
            <div class="text-white">
                <div class="inline-flex items-center gap-2 bg-white/10 rounded-full px-4 py-2 mb-5">
                    <div class="w-2 h-2 bg-accent rounded-full animate-pulse"></div>
                    <span class="text-sm font-semibold text-blue-100">Penerimaan Peserta Didik Baru</span>
                </div>
<div class="photo-slot admission-photo"><span class="slot-label"><i class="fa-regular fa-image" aria-hidden="true"></i> Kegiatan siswa atau penyambutan peserta didik</span><img class="slot-photo" src="{{ asset('images/landing/ppdb-siswa.jpg') }}" alt="Kegiatan siswa atau penyambutan peserta didik" loading="lazy" decoding="async" onerror="this.style.display='none'"></div>
                <h2 class="text-4xl font-black leading-tight mb-4">
                    PPDB Tahun Ajaran<br><span class="text-accent">2025/2026</span> Telah Dibuka!
                </h2>
                <p class="text-blue-100 leading-relaxed mb-8">
                    Bergabunglah dengan keluarga besar SMA Smart School. Daftarkan putra-putri Anda sekarang dan dapatkan pendidikan terbaik dengan fasilitas modern.
                </p>
                <div class="grid grid-cols-2 gap-4 mb-8">
                    @foreach([
                        ['icon'=>'fa-calendar','label'=>'Gelombang 1','value'=>'1 Jan – 28 Feb 2025'],
                        ['icon'=>'fa-calendar-check','label'=>'Gelombang 2','value'=>'1 Mar – 30 Apr 2025'],
                        ['icon'=>'fa-file-lines','label'=>'Tes Seleksi','value'=>'15 Mei 2025'],
                        ['icon'=>'fa-trophy','label'=>'Pengumuman','value'=>'25 Mei 2025'],
                    ] as $item)
                    <div class="bg-white/10 backdrop-blur rounded-xl p-4">
                        <i class="fa-solid {{ $item['icon'] }} text-accent mb-1.5 block"></i>
                        <p class="text-xs text-blue-200 font-medium">{{ $item['label'] }}</p>
                        <p class="text-sm font-bold text-white">{{ $item['value'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
            <!-- Form Pendaftaran Minat -->
            <div class="bg-white rounded-3xl p-8 shadow-2xl">
                <h3 class="text-xl font-black text-gray-800 mb-2">Daftar Minat Online</h3>
                <p class="text-sm text-gray-500 mb-6">Isi formulir ini dan tim kami akan menghubungi Anda</p>
                <form class="space-y-4" onsubmit="handlePPDB(event)">
                    <div>
                        <label for="nama" class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Calon Siswa *</label>
                        <input type="text" required id="nama" name="nama" autocomplete="name" placeholder="Nama lengkap" class="w-full px-4 py-2.5 border-2 border-gray-100 rounded-xl focus:outline-none focus:border-secondary transition bg-gray-50 text-sm">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label for="telepon" class="block text-sm font-semibold text-gray-700 mb-1.5">No. HP Orang Tua *</label>
                            <input type="tel" required id="telepon" name="telepon" autocomplete="tel" placeholder="08xx-xxxx-xxxx" class="w-full px-4 py-2.5 border-2 border-gray-100 rounded-xl focus:outline-none focus:border-secondary transition bg-gray-50 text-sm">
                        </div>
                        <div>
                            <label for="jurusan" class="block text-sm font-semibold text-gray-700 mb-1.5">Pilihan Jurusan</label>
                            <select id="jurusan" name="jurusan" class="w-full px-4 py-2.5 border-2 border-gray-100 rounded-xl focus:outline-none focus:border-secondary transition bg-gray-50 text-sm">
                                <option>IPA (MIPA)</option>
                                <option>IPS</option>
                                <option>Bahasa & TIK</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label for="asal-sekolah" class="block text-sm font-semibold text-gray-700 mb-1.5">Asal Sekolah *</label>
                        <input type="text" required id="asal-sekolah" name="asal-sekolah" autocomplete="organization" placeholder="Nama SMP/MTs asal" class="w-full px-4 py-2.5 border-2 border-gray-100 rounded-xl focus:outline-none focus:border-secondary transition bg-gray-50 text-sm">
                    </div>
                    <button type="submit" class="w-full py-3.5 bg-primary text-white rounded-xl font-bold hover:bg-secondary transition shadow-lg flex items-center justify-center gap-2">
                        <i class="fa-solid fa-paper-plane"></i> Kirim Pendaftaran
                    </button>
                    <p class="text-xs text-gray-400 text-center">Dengan mendaftar, Anda menyetujui syarat & ketentuan yang berlaku</p>
                </form>
                <div id="ppdb-success" role="status" aria-live="polite" class="hidden mt-4 bg-green-50 border border-green-200 rounded-xl p-4 text-center">
                    <i class="fa-solid fa-circle-check text-green-500 text-2xl mb-2 block"></i>
                    <p class="text-green-700 font-semibold text-sm">Formulir belum terhubung ke sistem pendaftaran.</p>
                    <p class="text-green-600 text-xs mt-1">Silakan hubungi kontak sekolah untuk melanjutkan pendaftaran.</p>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- ===== BERITA TERBARU ===== -->
<section id="berita" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex items-end justify-between mb-12">
            <div>
                <span class="inline-block px-4 py-1.5 bg-secondary/10 text-secondary rounded-full text-sm font-bold mb-3">Berita & Info</span>
                <h2 class="text-4xl font-black text-gray-800">Berita Terbaru</h2>
            </div>
            <a href="{{ route('login') }}" class="hidden sm:flex items-center gap-2 text-secondary font-semibold text-sm hover:underline">
                Lihat Semua <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @forelse($berita as $b)
            <div class="card-hover bg-white rounded-3xl overflow-hidden border border-gray-100 shadow-sm">
                <div class="news-photo h-44 bg-gradient-to-br from-primary/80 to-secondary flex items-center justify-center">
                    <div class="text-center text-white"><i class="fa-solid fa-newspaper text-4xl" aria-hidden="true"></i><p class="text-xs mt-2">{{ $b->kategori }}</p></div>
<img class="slot-photo" src="{{ $b->gambar ? Storage::url($b->gambar) : asset('images/landing/berita-default.jpg') }}" alt="{{ $b->judul }}" loading="lazy" decoding="async" onerror="this.style.display='none'">
                </div>
                <div class="p-5">
                    <span class="inline-block px-2.5 py-1 bg-secondary/10 text-secondary text-xs font-bold rounded-full mb-3 capitalize">{{ $b->kategori }}</span>
                    <h3 class="font-black text-gray-800 text-base leading-snug mb-2 line-clamp-2">{{ $b->judul }}</h3>
                    <p class="text-gray-500 text-sm leading-relaxed line-clamp-2 mb-4">{{ Str::limit(strip_tags($b->konten), 100) }}</p>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-400"><i class="fa-regular fa-calendar mr-1"></i>{{ $b->published_at?->format('d M Y') }}</span>
                        <a href="{{ route('login') }}" class="text-xs text-secondary font-bold hover:underline">Baca →</a>
                    </div>
                </div>
            </div>
            @empty
            @foreach([
                ['kat'=>'pengumuman','icon'=>'fa-bullhorn','judul'=>'PPDB 2025/2026 Resmi Dibuka','tgl'=>'01 Jan 2025','isi'=>'Pendaftaran peserta didik baru tahun ajaran 2025/2026 telah resmi dibuka. Segera daftarkan putra-putri Anda.'],
                ['kat'=>'prestasi','icon'=>'fa-trophy','judul'=>'Siswa Kita Raih Medali Emas OSN','tgl'=>'15 Des 2024','isi'=>'Siswa SMA Smart School berhasil meraih medali emas pada Olimpiade Sains Nasional tingkat provinsi.'],
                ['kat'=>'kegiatan','icon'=>'fa-calendar-days','judul'=>'Gelar Karya Siswa 2024 Sukses Digelar','tgl'=>'10 Des 2024','isi'=>'Kegiatan pameran hasil karya siswa yang menampilkan berbagai inovasi dan kreativitas berhasil digelar dengan meriah.'],
            ] as $bn)
            <div class="card-hover bg-white rounded-3xl overflow-hidden border border-gray-100 shadow-sm">
                <div class="news-photo h-44 bg-gradient-to-br from-primary/80 to-secondary flex items-center justify-center">
                    <div class="text-center text-white">
                        <img class="slot-photo" src="{{ asset('images/landing/berita-' . $bn['kat'] . '.jpg') }}" alt="{{ $bn['judul'] }}" loading="lazy" decoding="async" onerror="this.style.display='none'"><i class="fa-solid {{ $bn['icon'] }} text-5xl opacity-60 mb-2 block"></i>
                        <span class="text-xs uppercase font-bold tracking-wider opacity-70">{{ $bn['kat'] }}</span>
                    </div>
                </div>
                <div class="p-5">
                    <span class="inline-block px-2.5 py-1 bg-secondary/10 text-secondary text-xs font-bold rounded-full mb-3 capitalize">{{ $bn['kat'] }}</span>
                    <h3 class="font-black text-gray-800 text-base leading-snug mb-2">{{ $bn['judul'] }}</h3>
                    <p class="text-gray-500 text-sm leading-relaxed mb-4">{{ $bn['isi'] }}</p>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-400"><i class="fa-regular fa-calendar mr-1"></i>{{ $bn['tgl'] }}</span>
                        <a href="{{ route('login') }}" class="text-xs text-secondary font-bold hover:underline">Baca →</a>
                    </div>
                </div>
            </div>
            @endforeach
            @endforelse
        </div>
    </div>
</section>
<!-- ===== TESTIMONI ===== -->
<section data-page-extra="tentang" class="py-20 bg-slate-50">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-12">
            <span class="inline-block px-4 py-1.5 bg-secondary/10 text-secondary rounded-full text-sm font-bold mb-3">Testimoni</span>
            <h2 class="text-4xl font-black text-gray-800">Kata Mereka Tentang Kami</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach([
                ['nama'=>'Budi Santoso','role'=>'Orang Tua Siswa','avatar'=>'B','color'=>'bg-blue-500','msg'=>'Sistem Smart School sangat membantu saya memantau perkembangan anak dari rumah. Nilai dan absensi bisa dilihat kapan saja!'],
                ['nama'=>'Siti Aisyah','role'=>'Siswa Kelas XII IPA','avatar'=>'S','color'=>'bg-pink-500','msg'=>'Fitur konseling online sangat membantu. Saya bisa curhat dengan Guru BK tanpa harus malu bertemu langsung.'],
                ['nama'=>'Ahmad Fauzi, S.Pd','role'=>'Guru Matematika','avatar'=>'A','color'=>'bg-green-500','msg'=>'Input nilai dan absensi jadi jauh lebih mudah dan cepat. Sistem ini benar-benar mengubah cara kerja kami.'],
            ] as $t)
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 card-hover">
                <div class="flex gap-1 mb-4">
                    @for($i=0;$i<5;$i++)<i class="fa-solid fa-star text-accent text-sm"></i>@endfor
                </div>
                <p class="text-gray-600 leading-relaxed mb-5 text-sm">"{{ $t['msg'] }}"</p>
                <div class="flex items-center gap-3">
                    <div class="avatar-photo w-10 h-10 {{ $t['color'] }} rounded-xl flex items-center justify-center text-white font-bold">{{ $t['avatar'] }}<img class="slot-photo" src="{{ asset('images/landing/testimoni-' . $loop->iteration . '.jpg') }}" alt="{{ $t['nama'] }}" loading="lazy" decoding="async" onerror="this.style.display='none'"></div>
                    <div>
                        <p class="font-bold text-gray-800 text-sm">{{ $t['nama'] }}</p>
                        <p class="text-xs text-gray-400">{{ $t['role'] }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
<!-- ===== KONTAK & LOKASI ===== -->
<section id="kontak" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-14">
            <span class="inline-block px-4 py-1.5 bg-accent/10 text-accent rounded-full text-sm font-bold mb-3">Kontak & Lokasi</span>
            <h2 class="text-4xl font-black text-gray-800 mb-4">Temukan Kami</h2>
            <p class="text-gray-500">Kami siap membantu Anda. Hubungi kami melalui saluran berikut</p>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
            <!-- Info Kontak -->
            <div class="space-y-5">
                <div class="flex items-start gap-4 p-5 bg-gray-50 rounded-2xl">
                    <div class="w-12 h-12 bg-primary rounded-xl flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-location-dot text-white text-lg"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-800 mb-1">Alamat</h4>
                        <p class="text-gray-600 text-sm leading-relaxed">Jl. Pendidikan No. 1, Kel. Medan Baru,<br>Kec. Medan Selayang, Kota Medan,<br>Sumatera Utara 20154</p>
                    </div>
                </div>
                <div class="flex items-start gap-4 p-5 bg-gray-50 rounded-2xl">
                    <div class="w-12 h-12 bg-secondary rounded-xl flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-phone text-white text-lg"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-800 mb-1">Telepon & WhatsApp</h4>
                        <p class="text-gray-600 text-sm">(061) 123-4567</p>
                        <a href="https://wa.me/6281234567890" target="_blank" rel="noopener noreferrer"
                           class="inline-flex items-center gap-2 mt-2 px-4 py-2 bg-green-500 text-white rounded-xl text-xs font-bold hover:bg-green-600 transition">
                            <i class="fa-brands fa-whatsapp"></i> Chat WhatsApp
                        </a>
                    </div>
                </div>
                <div class="flex items-start gap-4 p-5 bg-gray-50 rounded-2xl">
                    <div class="w-12 h-12 bg-accent rounded-xl flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-envelope text-white text-lg"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-800 mb-1">Email</h4>
                        <p class="text-gray-600 text-sm">info@smartschool.sch.id</p>
                        <p class="text-gray-600 text-sm">ppdb@smartschool.sch.id</p>
                    </div>
                </div>
                <div class="flex items-start gap-4 p-5 bg-gray-50 rounded-2xl">
                    <div class="w-12 h-12 bg-indigo-500 rounded-xl flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-clock text-white text-lg"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-800 mb-1">Jam Operasional</h4>
                        <p class="text-gray-600 text-sm">Senin – Jumat: 07.00 – 15.00 WIB</p>
                        <p class="text-gray-600 text-sm">Sabtu: 07.00 – 12.00 WIB</p>
                        <p class="text-gray-400 text-xs mt-1">Minggu & Libur Nasional: Tutup</p>
                    </div>
                </div>
                <!-- Sosmed -->
                <div class="p-5 bg-gray-50 rounded-2xl">
                    <h4 class="font-bold text-gray-800 mb-3">Ikuti Kami</h4>
                    <div class="flex gap-3">
                        @foreach([
                            ['icon'=>'fa-brands fa-instagram','color'=>'bg-pink-500','label'=>'Instagram'],
                            ['icon'=>'fa-brands fa-facebook','color'=>'bg-blue-600','label'=>'Facebook'],
                            ['icon'=>'fa-brands fa-youtube','color'=>'bg-red-500','label'=>'YouTube'],
                            ['icon'=>'fa-brands fa-tiktok','color'=>'bg-gray-800','label'=>'TikTok'],
                        ] as $s)
                        <a href="#" class="w-10 h-10 {{ $s['color'] }} rounded-xl flex items-center justify-center text-white hover:opacity-80 transition" title="{{ $s['label'] }}">
                            <i class="{{ $s['icon'] }} text-sm"></i>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
            <!-- Peta -->
            <div class="rounded-3xl overflow-hidden shadow-xl border border-gray-100 bg-gray-100 min-h-[450px] flex flex-col">
                <div class="bg-primary px-5 py-3.5 flex items-center gap-2">
                    <i class="fa-solid fa-map text-white"></i>
                    <span class="text-white font-semibold text-sm">Lokasi SMA Smart School</span>
                </div>
<div class="photo-slot contact-photo"><span class="slot-label"><i class="fa-regular fa-image" aria-hidden="true"></i> Gerbang sekolah dan akses masuk</span><img class="slot-photo" src="{{ asset('images/landing/kontak-gerbang.jpg') }}" alt="Gerbang sekolah dan akses masuk" loading="lazy" decoding="async" onerror="this.style.display='none'"></div>
                <!-- Google Maps Embed (placeholder - ganti src dengan link embed maps asli) -->
                <iframe title="Peta lokasi sekolah"
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3981.913763278765!2d98.67638!3d3.58333!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zM8KwMzUnMDAuMCJOIDk4wrAwNCcwMC4wIkU!5e0!3m2!1sid!2sid!4v1234567890"
                    class="flex-1 w-full min-h-[400px]"
                    style="border:0;" allowfullscreen="" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>
    </div>
</section>
</main>
<!-- ===== FOOTER ===== -->
<footer class="bg-primary text-white">
    <div class="max-w-7xl mx-auto px-6 py-14">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-10">
            <!-- Brand -->
            <div class="md:col-span-2">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 bg-accent rounded-2xl flex items-center justify-center shadow-lg">
                        <i class="fa-solid fa-graduation-cap text-white text-xl"></i>
                    </div>
                    <div>
                        <p class="text-xl font-black">SMK Smart School</p>
                        <p class="text-blue-300 text-xs">Sekolah Unggulan Terpadu</p>
                    </div>
                </div>
                <p class="text-blue-200 text-sm leading-relaxed max-w-sm mb-5">
                    Mencetak generasi cerdas, berkarakter, dan siap menghadapi tantangan global melalui pendidikan berkualitas tinggi berbasis teknologi.
                </p>
                <div class="flex gap-2">
                    @foreach([
                        ['icon'=>'fa-brands fa-instagram','label'=>'Instagram'],
                        ['icon'=>'fa-brands fa-facebook','label'=>'Facebook'],
                        ['icon'=>'fa-brands fa-youtube','label'=>'YouTube'],
                        ['icon'=>'fa-brands fa-tiktok','label'=>'TikTok'],
                    ] as $s)
                    <a href="#" class="w-9 h-9 bg-white/10 hover:bg-accent rounded-xl flex items-center justify-center transition" title="{{ $s['label'] }}">
                        <i class="{{ $s['icon'] }} text-sm"></i>
                    </a>
                    @endforeach
                </div>
            </div>
            <!-- Links -->
            <div>
                <h4 class="font-bold text-sm mb-4 text-blue-200 uppercase tracking-wider">Menu</h4>
                <ul class="space-y-2.5">
                    @foreach(['Beranda'=>'#beranda','Tentang Kami'=>'#tentang','Program Studi'=>'#program','Fasilitas'=>'#fasilitas','Berita'=>'#berita','PPDB 2025/2026'=>'#ppdb'] as $label => $href)
                    <li><a href="{{ $href }}" class="text-sm text-blue-200 hover:text-accent transition flex items-center gap-1.5"><i class="fa-solid fa-chevron-right text-xs"></i>{{ $label }}</a></li>
                    @endforeach
                </ul>
            </div>
            <!-- Kontak -->
            <div>
                <h4 class="font-bold text-sm mb-4 text-blue-200 uppercase tracking-wider">Kontak</h4>
                <ul class="space-y-3">
                    <li class="flex items-start gap-2 text-sm text-blue-200">
                        <i class="fa-solid fa-location-dot mt-0.5 text-accent flex-shrink-0"></i>
                        Jl. Pendidikan No. 1, Medan 20154
                    </li>
                    <li class="flex items-center gap-2 text-sm text-blue-200">
                        <i class="fa-solid fa-phone text-accent flex-shrink-0"></i>
                        (061) 123-4567
                    </li>
                    <li class="flex items-center gap-2 text-sm text-blue-200">
                        <i class="fa-solid fa-envelope text-accent flex-shrink-0"></i>
                        info@smartschool.sch.id
                    </li>
                    <li class="flex items-center gap-2 text-sm text-blue-200">
                        <i class="fa-solid fa-globe text-accent flex-shrink-0"></i>
                        www.smartschool.sch.id
                    </li>
                </ul>
                <div class="mt-5">
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-accent text-white rounded-xl font-bold text-sm hover:bg-orange-400 transition shadow-lg">
                        <i class="fa-solid fa-right-to-bracket"></i> Masuk Sistem
                    </a>
                </div>
            </div>
        </div>
        <div class="border-t border-white/10 pt-6 flex flex-col sm:flex-row items-center justify-between gap-3">
            <p class="text-blue-300 text-sm">© {{ date('Y') }} SMK Smart School. Hak cipta dilindungi.</p>
            <p class="text-blue-300 text-sm">Belajar hari ini. Bersinar esok hari.</p>
        </div>
    </div>
</footer>
<script>
// Scroll animation
const observer = new IntersectionObserver((entries) => {
    entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('visible'); });
}, { threshold: 0.1 });
document.querySelectorAll('.fade-in').forEach(el => observer.observe(el));
// PPDB form
function handlePPDB(e) {
    e.preventDefault();
    document.getElementById('ppdb-success').classList.remove('hidden');

}
// All sections remain visible; native anchor links align below the fixed navbar.
(() => {
    const sections = [...document.querySelectorAll('#main-content > section[id]')];
    const links = [...document.querySelectorAll('nav a[href^="#"]')];
    let scheduled = false;
    function updateActiveMenu() {
        scheduled = false;
        const navbar = document.querySelector('nav');
        const offset = navbar.getBoundingClientRect().height + 32;
        let current = sections[0];
        for (const section of sections) {
            if (section.getBoundingClientRect().top <= offset) current = section;
        }
        links.forEach(link => {
            const selected = link.getAttribute('href') === '#' + current.id;
            link.classList.toggle('is-active', selected);
            if (selected) link.setAttribute('aria-current', 'location');
            else link.removeAttribute('aria-current');
        });
    }
    function scheduleUpdate() {
        if (!scheduled) { scheduled = true; requestAnimationFrame(updateActiveMenu); }
    }
    window.addEventListener('scroll', scheduleUpdate, { passive: true });
    window.addEventListener('resize', scheduleUpdate);
    window.addEventListener('hashchange', scheduleUpdate);
    updateActiveMenu();
})();
</script>
<script>
// Paginate extra articles on desktop, preserving access to every news item.
(() => {
 const grid = document.querySelector('#berita > .max-w-7xl > .grid');
 const cards = [...grid.children].filter(el => el.classList.contains('card-hover'));
 if (cards.length <= 3) return;
 const controls = document.createElement('div');
 controls.className = 'news-pagination';
 controls.innerHTML = '<button type="button" aria-label="Berita sebelumnya">← Sebelumnya</button><span aria-live="polite"></span><button type="button" aria-label="Berita berikutnya">Berikutnya →</button>';
 grid.after(controls);
 const [prev, next] = controls.querySelectorAll('button');
 const label = controls.querySelector('span');
 const desktop = window.matchMedia('(min-width:1024px)');
 let page = 0;
 function render() {
  controls.hidden = !desktop.matches;
  cards.forEach((card, i) => { card.hidden = desktop.matches && Math.floor(i / 3) !== page; });
  prev.disabled = page === 0;
  next.disabled = page === Math.ceil(cards.length / 3) - 1;
  label.textContent = (page + 1) + ' / ' + Math.ceil(cards.length / 3);
 }
 prev.addEventListener('click', () => { if (page > 0) { page--; render(); } });
 next.addEventListener('click', () => { if ((page + 1) * 3 < cards.length) { page++; render(); } });
 desktop.addEventListener('change', render);
 render();
})();
</script>
</body>
</html>
