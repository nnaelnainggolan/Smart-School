@props(['name', 'alt', 'uploaded' => null])

@php
    // Pixel viewports in the supplied 1672 x 941 collage, excluding gutters and labels.
    // A separate photo with the original slot filename can override each viewport.
    $crops = [
        'tentang-sekolah.jpg' => [6, 5, 329, 192],
        'program-ipa.jpg' => [345, 5, 323, 192],
        'program-ips.jpg' => [676, 5, 322, 192],
        'program-bahasa-tik.jpg' => [1007, 5, 325, 192],
        'fasilitas-lab-ipa.jpg' => [1342, 5, 327, 192],
        'fasilitas-lab-komputer.jpg' => [1007, 5, 325, 192],
        'fasilitas-perpustakaan.jpg' => [6, 255, 329, 182],
        'fasilitas-olahraga.jpg' => [345, 255, 323, 182],
        'fasilitas-kantin.jpg' => [676, 255, 322, 182],
        'fasilitas-musholla.jpg' => [1007, 255, 325, 182],
        'fasilitas-wifi.jpg' => [1342, 255, 327, 182],
        'fasilitas-antar-jemput.jpg' => [6, 497, 329, 153],
        'ppdb-siswa.jpg' => [345, 497, 323, 153],
        'berita-pengumuman.jpg' => [676, 497, 322, 153],
        'berita-prestasi.jpg' => [1007, 497, 325, 153],
        'berita-kegiatan.jpg' => [1342, 497, 327, 153],
        'berita-default.jpg' => [6, 714, 329, 170],
        'testimoni-1.jpg' => [433, 723, 165, 165],
        'testimoni-2.jpg' => [757, 723, 165, 165],
        'testimoni-3.jpg' => [1093, 723, 165, 165],
        'kontak-gerbang.jpg' => [1342, 714, 327, 170],
    ];
    $crop = $crops[$name] ?? $crops['berita-default.jpg'];
    $hasPhoto = array_key_exists($name, $crops) && is_file(public_path('images/landing/' . $name));
@endphp

<svg {{ $attributes->class(['slot-photo']) }} viewBox="{{ implode(' ', $crop) }}" preserveAspectRatio="xMidYMid slice" role="img" aria-label="{{ $alt }}" focusable="false" style="overflow: hidden">
    <image href="{{ asset('images/landing/school-showcase.png') }}" width="1672" height="941" />
</svg>
@if($uploaded || $hasPhoto)
    <img class="slot-photo" src="{{ $uploaded ?: asset('images/landing/' . $name) }}" alt="{{ $alt }}" loading="lazy" decoding="async" onload="this.previousElementSibling.setAttribute('aria-hidden', 'true')" onerror="this.style.display='none'; this.previousElementSibling.removeAttribute('aria-hidden')">
@endif
