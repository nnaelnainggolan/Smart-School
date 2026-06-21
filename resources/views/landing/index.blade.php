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
            theme: { extend: { colors: { primary: '#1E3A5F', secondary: '#2E86AB', accent: '#F4A261' } } }
        }
    </script>
    <style>
        html { scroll-behavior: smooth; }
        body { font-family: 'Segoe UI', system-ui, sans-serif; }
        .gradient-text { background: linear-gradient(135deg, #1E3A5F, #2E86AB); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        .hero-bg {
        background-image: linear-gradient(rgba(30,58,95,0.85), rgba(30,58,95,0.85)), url('/images/sekolah-1.jpg') !important;
        background-size: cover !important;
        background-position: center !important;
        }
        .card-hover { transition: transform 0.3s ease, box-shadow 0.3s ease; }
        .card-hover:hover { transform: translateY(-6px); box-shadow: 0 20px 40px rgba(30,58,95,0.15); }
        .nav-link { transition: color 0.2s; }
        .nav-link:hover { color: #F4A261; }
        .floating { animation: floating 3s ease-in-out infinite; }
        @keyframes floating { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-10px); } }
        .fade-in { opacity: 0; transform: translateY(20px); transition: opacity 0.6s ease, transform 0.6s ease; }
        .fade-in.visible { opacity: 1; transform: translateY(0); }
        .pattern-bg { background-image: radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px); background-size: 30px 30px; }
    </style>
</head>
<body class="bg-white">

<!-- ===== NAVBAR ===== -->
<nav class="fixed top-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-md shadow-sm border-b border-gray-100" x-data="{ open: false }">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <div class="max-w-7xl mx-auto px-6 py-3.5 flex items-center justify-between">
        <!-- Logo -->
        <a href="#" class="flex items-center gap-3">
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
        <button @click="open = !open" class="lg:hidden p-2 text-gray-600">
            <i :class="open ? 'fa-solid fa-xmark' : 'fa-solid fa-bars'" class="text-xl"></i>
        </button>
    </div>
    <!-- Mobile Menu -->
    <div x-show="open" x-cloak class="lg:hidden bg-white border-t border-gray-100 px-6 py-4 space-y-3">
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

<!-- ===== HERO ===== -->
<section id="beranda" class="hero-bg min-h-screen flex items-center pt-16 relative overflow-hidden pattern-bg">
    
    <div class="max-w-7xl mx-auto px-6 py-20 relative">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div class="text-white">
                <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur rounded-full px-4 py-2 mb-6">
                    <div class="w-2 h-2 bg-accent rounded-full animate-pulse"></div>
                    <span class="text-sm font-medium text-blue-100">PPDB 2025/2026 Dibuka!</span>
                </div>
                <h1 class="text-4xl lg:text-6xl font-black leading-tight mb-7">
                    Sekolah Terbaik<br>
                    <span class="text-accent">SMA N 2 MEDAN</span><br>
                    Unggul Indonesia
                </h1>
                <p class="text-blue-100 text-lg leading-relaxed mb-8 max-w-lg">
                    SMA Smart School hadir dengan sistem pembelajaran modern, fasilitas lengkap, dan tenaga pendidik berpengalaman untuk mencetak generasi cerdas dan berkarakter.
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="#ppdb" class="px-8 py-3.5 bg-accent text-white rounded-2xl font-bold text-base hover:bg-orange-400 transition shadow-xl hover:shadow-accent/40 flex items-center gap-2">
                        <i class="fa-solid fa-pen-to-square"></i> Daftar Sekarang
                    </a>
                    <a href="#tentang" class="px-8 py-3.5 bg-white/15 backdrop-blur text-white border-2 border-white/30 rounded-2xl font-bold text-base hover:bg-white/25 transition flex items-center gap-2">
                        <i class="fa-solid fa-play-circle"></i> Pelajari Lebih
                    </a>
                </div>
                <!-- Quick Stats -->
                <div class="grid grid-cols-3 gap-4 mt-10 pt-8 border-t border-white/20">
                    <div>
                        <p class="text-3xl font-black text-accent">15+</p>
                        <p class="text-blue-200 text-xs mt-0.5">Tahun Berdiri</p>
                    </div>
                    <div>
                        <p class="text-3xl font-black text-accent">1200+</p>
                        <p class="text-blue-200 text-xs mt-0.5">Siswa Aktif</p>
                    </div>
                    <div>
                        <p class="text-3xl font-black text-accent">98%</p>
                        <p class="text-blue-200 text-xs mt-0.5">Kelulusan</p>
                    </div>
                </div>
            </div>
            <!-- Hero Image / Illustration -->
            <!-- Hero Image -->
                    <!-- Hero Image -->
                    <div class="hidden lg:flex justify-center">
                        <div class="relative">
                            <img src="{{ asset('images/siswa.png') }}" alt="Siswa Smart School" class="w-[40rem] h-auto drop-shadow-2xl relative z-10">
                        </div>
                    </div>
                    <!-- Badge floating -->
                    
                    
                </div>
            </div>
        </div>
    </div>
    <!-- Wave -->
    <div class="absolute bottom-0 left-0 right-0">
        <svg viewBox="0 0 1440 80" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0,40 C360,80 1080,0 1440,40 L1440,80 L0,80 Z" fill="white"/>
        </svg>
    </div>
</section>

<!-- ===== TENTANG ===== -->
<section id="tentang" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <!-- Left: Image placeholder + stats -->
            <div class="relative">
                <div class="bg-gradient-to-br from-primary to-secondary rounded-3xl overflow-hidden aspect-[4/3] flex items-center justify-center shadow-2xl">
                    <!-- Foto Sekolah Placeholder dengan ilustrasi -->
                    <div class="text-center text-white p-8">
                        <div class="w-24 h-24 bg-white/20 rounded-3xl flex items-center justify-center mx-auto mb-4">
                            <i class="fa-solid fa-school text-5xl text-white"></i>
                        </div>
                        <p class="font-bold text-xl">SMA Smart School</p>
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
                    SMA Smart School adalah sekolah menengah atas unggulan yang berdiri sejak tahun 2009 di kota Medan. Kami berkomitmen untuk memberikan pendidikan berkualitas tinggi dengan menggabungkan kurikulum nasional dan pengembangan karakter.
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
                ['icon'=>'fa-flask','color'=>'from-blue-500 to-blue-700','title'=>'IPA (MIPA)','desc'=>'Program unggulan sains dan matematika dengan laboratorium modern dan guru berpengalaman','tags'=>['Matematika','Fisika','Kimia','Biologi'],'badge'=>'Paling Diminati'],
                ['icon'=>'fa-chart-line','color'=>'from-green-500 to-green-700','title'=>'IPS','desc'=>'Program ilmu sosial yang membangun pemahaman mendalam tentang ekonomi, geografi dan sejarah','tags'=>['Ekonomi','Geografi','Sosiologi','Sejarah'],'badge'=>null],
                ['icon'=>'fa-code','color'=>'from-purple-500 to-purple-700','title'=>'Bahasa & TIK','desc'=>'Program bahasa dan teknologi informasi untuk mempersiapkan generasi digital yang komunikatif','tags'=>['Bahasa Inggris','Bahasa Arab','Komputer','Multimedia'],'badge'=>'Baru'],
            ] as $p)
            <div class="card-hover bg-white rounded-3xl overflow-hidden shadow-md border border-gray-100">
                <div class="h-36 bg-gradient-to-br {{ $p['color'] }} flex items-center justify-center relative">
                    <i class="fa-solid {{ $p['icon'] }} text-6xl text-white/80"></i>
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
                ['icon'=>'fa-microscope','color'=>'bg-blue-50 text-blue-600','title'=>'Lab IPA Lengkap','desc'=>'3 laboratorium fisika, kimia & biologi'],
                ['icon'=>'fa-computer','color'=>'bg-purple-50 text-purple-600','title'=>'Lab Komputer','desc'=>'50 unit PC dengan internet cepat'],
                ['icon'=>'fa-book-open','color'=>'bg-green-50 text-green-600','title'=>'Perpustakaan','desc'=>'5000+ koleksi buku & e-library'],
                ['icon'=>'fa-futbol','color'=>'bg-orange-50 text-orange-600','title'=>'Lapangan Olahraga','desc'=>'Lapangan basket, futsal & voli'],
                ['icon'=>'fa-utensils','color'=>'bg-red-50 text-red-600','title'=>'Kantin Sehat','desc'=>'Menu bergizi & terjangkau'],
                ['icon'=>'fa-mosque','color'=>'bg-teal-50 text-teal-600','title'=>'Musholla','desc'=>'Tempat ibadah yang nyaman'],
                ['icon'=>'fa-wifi','color'=>'bg-indigo-50 text-indigo-600','title'=>'WiFi Campus','desc'=>'Internet berkecepatan tinggi'],
                ['icon'=>'fa-bus','color'=>'bg-yellow-50 text-yellow-600','title'=>'Antar Jemput','desc'=>'Layanan transportasi siswa'],
            ] as $f)
            <div class="card-hover p-5 rounded-2xl border border-gray-100 text-center bg-white shadow-sm">
                <div class="w-14 h-14 {{ $f['color'] }} rounded-2xl flex items-center justify-center mx-auto mb-3">
                    <i class="fa-solid {{ $f['icon'] }} text-2xl"></i>
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
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Calon Siswa *</label>
                        <input type="text" required placeholder="Nama lengkap" class="w-full px-4 py-2.5 border-2 border-gray-100 rounded-xl focus:outline-none focus:border-secondary transition bg-gray-50 text-sm">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">No. HP Orang Tua *</label>
                            <input type="tel" required placeholder="08xx-xxxx-xxxx" class="w-full px-4 py-2.5 border-2 border-gray-100 rounded-xl focus:outline-none focus:border-secondary transition bg-gray-50 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Pilihan Jurusan</label>
                            <select class="w-full px-4 py-2.5 border-2 border-gray-100 rounded-xl focus:outline-none focus:border-secondary transition bg-gray-50 text-sm">
                                <option>IPA (MIPA)</option>
                                <option>IPS</option>
                                <option>Bahasa & TIK</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Asal Sekolah *</label>
                        <input type="text" required placeholder="Nama SMP/MTs asal" class="w-full px-4 py-2.5 border-2 border-gray-100 rounded-xl focus:outline-none focus:border-secondary transition bg-gray-50 text-sm">
                    </div>
                    <button type="submit" class="w-full py-3.5 bg-primary text-white rounded-xl font-bold hover:bg-secondary transition shadow-lg flex items-center justify-center gap-2">
                        <i class="fa-solid fa-paper-plane"></i> Kirim Pendaftaran
                    </button>
                    <p class="text-xs text-gray-400 text-center">Dengan mendaftar, Anda menyetujui syarat & ketentuan yang berlaku</p>
                </form>
                <div id="ppdb-success" class="hidden mt-4 bg-green-50 border border-green-200 rounded-xl p-4 text-center">
                    <i class="fa-solid fa-circle-check text-green-500 text-2xl mb-2 block"></i>
                    <p class="text-green-700 font-semibold text-sm">Pendaftaran berhasil dikirim!</p>
                    <p class="text-green-600 text-xs mt-1">Tim kami akan menghubungi Anda segera</p>
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
                <div class="h-44 bg-gradient-to-br from-primary/80 to-secondary flex items-center justify-center">
                    @if($b->gambar)
                        <img src="{{ Storage::url($b->gambar) }}" class="w-full h-full object-cover" alt="{{ $b->judul }}">
                    @else
                        <div class="text-center text-white">
                            <i class="fa-solid {{ $b->kategori === 'prestasi' ? 'fa-trophy' : ($b->kategori === 'pengumuman' ? 'fa-bullhorn' : 'fa-newspaper') }} text-5xl opacity-60 mb-2 block"></i>
                            <span class="text-xs uppercase font-bold tracking-wider opacity-70">{{ $b->kategori }}</span>
                        </div>
                    @endif
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
                ['kat'=>'kegiatan','icon'=>'fa-calendar-star','judul'=>'Gelar Karya Siswa 2024 Sukses Digelar','tgl'=>'10 Des 2024','isi'=>'Kegiatan pameran hasil karya siswa yang menampilkan berbagai inovasi dan kreativitas berhasil digelar dengan meriah.'],
            ] as $bn)
            <div class="card-hover bg-white rounded-3xl overflow-hidden border border-gray-100 shadow-sm">
                <div class="h-44 bg-gradient-to-br from-primary/80 to-secondary flex items-center justify-center">
                    <div class="text-center text-white">
                        <i class="fa-solid {{ $bn['icon'] }} text-5xl opacity-60 mb-2 block"></i>
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
<section class="py-20 bg-slate-50">
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
                    <div class="w-10 h-10 {{ $t['color'] }} rounded-xl flex items-center justify-center text-white font-bold">{{ $t['avatar'] }}</div>
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
                        <a href="https://wa.me/6281234567890" target="_blank"
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
                <!-- Google Maps Embed (placeholder - ganti src dengan link embed maps asli) -->
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3981.913763278765!2d98.67638!3d3.58333!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zM8KwMzUnMDAuMCJOIDk4wrAwNCcwMC4wIkU!5e0!3m2!1sid!2sid!4v1234567890"
                    class="flex-1 w-full min-h-[400px]"
                    style="border:0;" allowfullscreen="" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>
    </div>
</section>

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
                        <p class="text-xl font-black">SMA Smart School</p>
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
            <p class="text-blue-300 text-sm">© {{ date('Y') }} SMA Smart School. Hak cipta dilindungi.</p>
            <p class="text-blue-300 text-sm">Dibangun dengan <i class="fa-solid fa-heart text-accent mx-1"></i> menggunakan <strong>Laravel & Smart School System</strong></p>
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
    e.target.style.display = 'none';
}

// Active nav on scroll
window.addEventListener('scroll', () => {
    const sections = document.querySelectorAll('section[id]');
    const navLinks = document.querySelectorAll('.nav-link');
    sections.forEach(section => {
        const rect = section.getBoundingClientRect();
        if (rect.top <= 80 && rect.bottom >= 80) {
            navLinks.forEach(link => {
                link.style.color = link.getAttribute('href') === '#' + section.id ? '#F4A261' : '';
            });
        }
    });
});
</script>
</body>
</html>
