<?php

namespace App\Services;

use App\Models\Absensi;
use App\Models\Kelas;
use App\Models\Konseling;
use App\Models\Nilai;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class DashboardInsights
{
    public function forUser(User $user, ?Siswa $child = null): array
    {
        $period = SchoolContext::period();
        $caption = $period['tahun_ajaran'].' · Semester '.$period['semester'];
        if ($user->role === 'admin') {
            $classes = Kelas::withCount(['siswa' => fn ($q) => $q->where('status', 'aktif')])->orderBy('nama_kelas')->get();

            return [
                $this->attendance(Absensi::query()),
                $this->chart('Siswa per kelas', 'Siswa aktif · seluruh kelas', $classes->map(fn ($k) => ['label' => $k->nama_kelas, 'value' => $k->siswa_count])->all(), 'siswa'),
            ];
        }
        if ($user->role === 'guru') {
            $grades = Nilai::where('guru_id', $user->guru->id)->where($period);

            return [
                $this->attendance(Absensi::where('guru_id', $user->guru->id)),
                $this->chart('Kelengkapan nilai', $caption.' · rekaman yang Anda input', [
                    ['label' => 'Nilai akhir tersedia', 'value' => (clone $grades)->whereNotNull('nilai_akhir')->count()],
                    ['label' => 'Belum lengkap', 'value' => (clone $grades)->whereNull('nilai_akhir')->count(), 'tone' => 'gold'],
                ], 'rekaman', 'Belum ada rekaman nilai periode ini. Grafik tidak menghitung siswa yang belum memiliki rekaman.'),
            ];
        }
        if ($user->role === 'guru_bk') {
            $query = Konseling::where('guru_bk_id', $user->guru->id);
            $rows = [];
            for ($i = 5; $i >= 0; $i--) {
                $start = now()->startOfMonth()->subMonths($i);
                $rows[] = ['label' => $start->locale('id')->translatedFormat('M Y'), 'value' => (clone $query)->where('created_at', '>=', $start)->where('created_at', '<', $start->copy()->addMonth())->count()];
            }
            $counts = (clone $query)->selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status');

            return [
                $this->chart('Tren permintaan konseling', '6 bulan terakhir · ditugaskan kepada Anda · tanggal pengajuan', $rows, 'permintaan', 'Belum ada permintaan yang ditugaskan kepada Anda pada rentang ini.', 'line'),
                $this->chart('Status pendampingan', 'Seluruh periode · ditugaskan kepada Anda', collect(['pending' => 'Menunggu', 'disetujui' => 'Disetujui', 'berlangsung' => 'Berlangsung', 'selesai' => 'Selesai', 'ditolak' => 'Ditolak'])->map(fn ($label, $key) => ['label' => $label, 'value' => (int) $counts->get($key, 0)])->values()->all(), 'sesi'),
            ];
        }
        $student = $user->role === 'siswa' ? $user->siswa : $child;
        if (! $student) {
            return [];
        }
        $grades = Nilai::with('mataPelajaran')->where('siswa_id', $student->id)->where($period)->get();

        return [
            $this->attendance(Absensi::where('siswa_id', $student->id)),
            $this->chart('Nilai per mata pelajaran', $caption.' · nilai akhir yang sudah dihitung', $grades->map(fn ($n) => [
                'label' => $n->mataPelajaran->nama_mapel,
                'value' => $n->nilai_akhir === null ? null : (float) $n->nilai_akhir,
                'tone' => $n->nilai_akhir !== null && $n->nilai_akhir < $n->mataPelajaran->kkm ? 'rose' : 'forest',
                'note' => 'KKM '.$n->mataPelajaran->kkm,
            ])->all(), 'poin', 'Belum ada nilai akhir periode ini. Nilai yang belum diisi tidak dihitung sebagai nol.', 'bar', 100),
        ];
    }

    private function attendance(Builder $query): array
    {
        $rows = [];
        for ($i = 6; $i >= 0; $i--) {
            $day = today()->subDays($i);
            $counts = (clone $query)->whereDate('tanggal', $day)->selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status');
            $total = (int) $counts->sum();
            $rows[] = ['label' => $day->format('d/m'), 'value' => $total ? round(100 * $counts->get('Hadir', 0) / $total, 1) : null, 'note' => $total.' rekaman'];
        }

        return $this->chart('Tren kehadiran', '7 hari terakhir · persentase Hadir dari absensi yang tercatat', $rows, '%', 'Belum ada absensi pada rentang ini. Hari tanpa rekaman tidak dihitung sebagai 0%.', 'line', 100);
    }

    private function chart(string $title, string $caption, array $rows, string $unit, string $empty = 'Belum ada data untuk ditampilkan.', string $type = 'bar', ?int $maximum = null): array
    {
        $values = array_column($rows, 'value');
        $hasData = count(array_filter($values, fn ($v) => $v !== null)) > 0;
        if ($unit !== '%' && $unit !== 'poin') {
            $hasData = array_sum($values) > 0;
        }

        return compact('title', 'caption', 'rows', 'unit', 'empty', 'type', 'hasData') + ['maximum' => $maximum ?? max(1, ...$values)];
    }
}
