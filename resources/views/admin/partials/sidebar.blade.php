<a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
    <i class="fa-solid fa-house"></i><span x-show="sidebarOpen">Dashboard</span>
</a>

<p class="sidebar-section" x-show="sidebarOpen">Akademik</p>
<a href="{{ route('admin.siswa.index') }}" class="sidebar-link {{ request()->routeIs('admin.siswa*') ? 'active' : '' }}">
    <i class="fa-solid fa-user-graduate"></i><span x-show="sidebarOpen">Data Siswa</span>
</a>
<a href="{{ route('admin.guru.index') }}" class="sidebar-link {{ request()->routeIs('admin.guru*') ? 'active' : '' }}">
    <i class="fa-solid fa-chalkboard-user"></i><span x-show="sidebarOpen">Data Guru</span>
</a>
<a href="{{ route('admin.orangtua.index') }}" class="sidebar-link {{ request()->routeIs('admin.orangtua*') ? 'active' : '' }}">
    <i class="fa-solid fa-people-roof"></i><span x-show="sidebarOpen">Data Orang Tua</span>
</a>
<a href="{{ route('admin.kelas.index') }}" class="sidebar-link {{ request()->routeIs('admin.kelas*') ? 'active' : '' }}">
    <i class="fa-solid fa-building-columns"></i><span x-show="sidebarOpen">Data Kelas</span>
</a>
<a href="{{ route('admin.mapel.index') }}" class="sidebar-link {{ request()->routeIs('admin.mapel*') ? 'active' : '' }}">
    <i class="fa-solid fa-book-open"></i><span x-show="sidebarOpen">Mata Pelajaran</span>
</a>
<a href="{{ route('admin.jadwal.index') }}" class="sidebar-link {{ request()->routeIs('admin.jadwal*') ? 'active' : '' }}">
    <i class="fa-solid fa-calendar-days"></i><span x-show="sidebarOpen">Jadwal Pelajaran</span>
</a>

<p class="sidebar-section" x-show="sidebarOpen">Informasi</p>
<a href="{{ route('admin.berita.index') }}" class="sidebar-link {{ request()->routeIs('admin.berita*') ? 'active' : '' }}">
    <i class="fa-solid fa-newspaper"></i><span x-show="sidebarOpen">Berita & Pengumuman</span>
</a>
<a href="{{ route('admin.kalender.index') }}" class="sidebar-link {{ request()->routeIs('admin.kalender*') ? 'active' : '' }}">
    <i class="fa-solid fa-calendar-check"></i><span x-show="sidebarOpen">Kalender Akademik</span>
</a>

<p class="sidebar-section" x-show="sidebarOpen">Laporan</p>
<a href="{{ route('admin.laporan.index') }}" class="sidebar-link {{ request()->routeIs('admin.laporan*') ? 'active' : '' }}">
    <i class="fa-solid fa-chart-pie"></i><span x-show="sidebarOpen">Laporan Sistem</span>
</a>

<p class="sidebar-section" x-show="sidebarOpen">Akun</p>
<a href="{{ route('profile.edit') }}" class="sidebar-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
    <i class="fa-solid fa-user-gear"></i><span x-show="sidebarOpen">Profil Saya</span>
</a>
