<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Berita;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Konseling;
use App\Models\Siswa;
use App\Services\DashboardInsights;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_siswa' => Siswa::where('status', 'aktif')->count(),
            'total_guru' => Guru::count(),
            'total_kelas' => Kelas::count(),
            'konseling_pending' => Konseling::where('status', 'pending')->count(),
        ];

        $berita_terbaru = Berita::with('user')->where('is_published', true)
            ->latest('published_at')->take(5)->get();

        $absensi_hari_ini = Absensi::whereDate('tanggal', today())
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')->get()->pluck('total', 'status');

        $insights = app(DashboardInsights::class)->forUser(auth()->user());

        return view('admin.dashboard', compact('insights', 'stats', 'berita_terbaru', 'absensi_hari_ini'));
    }
}
