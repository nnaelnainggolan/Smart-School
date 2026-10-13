<?php

namespace App\Http\Controllers\GuruBK;

use App\Http\Controllers\Controller;
use App\Models\Konseling;
use App\Models\Siswa;
use App\Services\DashboardInsights;

class DashboardController extends Controller
{
    public function index()
    {
        $guru = auth()->user()->guru;
        $stats = [
            'pending' => Konseling::where('status', 'pending')->where(fn ($q) => $q->whereNull('guru_bk_id')->orWhere('guru_bk_id', $guru->id))->count(),
            'berlangsung' => Konseling::where('guru_bk_id', $guru->id)->where('status', 'berlangsung')->count(),
            'selesai' => Konseling::where('guru_bk_id', $guru->id)->where('status', 'selesai')->count(),
            'total_siswa' => Siswa::where('status', 'aktif')->count(),
        ];
        $konseling_terbaru = Konseling::with(['siswa.user'])->where(fn ($q) => $q->whereNull('guru_bk_id')->orWhere('guru_bk_id', $guru->id))->latest()->take(5)->get();

        $insights = app(DashboardInsights::class)->forUser(auth()->user());

        return view('guru_bk.dashboard', compact('insights', 'stats', 'konseling_terbaru'));
    }
}
