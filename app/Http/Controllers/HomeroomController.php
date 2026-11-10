<?php

namespace App\Http\Controllers;

use App\Models\FollowUp;
use App\Models\Konseling;
use App\Models\Siswa;
use App\Services\HomeroomAccess;
use App\Services\SchoolContext;
use Illuminate\Http\Request;

class HomeroomController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        abort_unless(in_array($user->role, ['admin', 'guru', 'guru_bk']), 403);
        $q = Siswa::with(['user', 'kelas'])->where('status', 'aktif');
        if ($user->role === 'guru') {
            $q->whereIn('kelas_id', HomeroomAccess::classes()->pluck('id'));
        }
        if ($user->role === 'guru_bk') {
            $q->whereIn('id', Konseling::where('guru_bk_id', $user->guru?->id)->select('siswa_id'));
        }
        $q->withCount(['absensi as alpha_count' => fn ($q) => $q->where('status', 'Alpha')->whereDate('tanggal', '>=', today()->subDays(config('school.lookback_days')))->whereDate('tanggal', '<=', today())]);
        $period = SchoolContext::period();
        [$y1,$y2] = array_map('intval', explode('/', $period['tahun_ajaran']));
        $previous = $period['semester'] === '2' ? ['tahun_ajaran' => $period['tahun_ajaran'], 'semester' => '1'] : ['tahun_ajaran' => ($y1 - 1).'/'.($y2 - 1), 'semester' => '2'];
        $q->withAvg(['nilai as current_average' => fn ($q) => $q->where($period)], 'nilai_akhir')
            ->withAvg(['nilai as previous_average' => fn ($q) => $q->where($previous)], 'nilai_akhir');
        $students = $q->orderByDesc('alpha_count')->paginate(20);
        $notes = FollowUp::with('siswa.user')->whereIn('siswa_id', $students->modelKeys())->latest()->get()->groupBy('siswa_id');

        return view('school.page', ['title' => 'Wali Kelas & Tindak Lanjut', 'description' => 'Sinyal berdasarkan absensi per pelajaran; periksa konteks sebelum melakukan tindak lanjut.', 'body' => 'school.homeroom', 'students' => $students, 'notes' => $notes]);
    }

    private function allow(Siswa $s)
    {
        if (auth()->user()->role === 'guru_bk') {
            abort_unless(Konseling::where('siswa_id', $s->id)->where('guru_bk_id', auth()->user()->guru?->id)->exists(), 403);
        } else {
            HomeroomAccess::student($s);
        }
    }

    public function store(Request $r)
    {
        $d = $r->validate(['siswa_id' => 'required|exists:siswa,id', 'note' => 'required|string|max:2000', 'due_date' => 'nullable|date_format:Y-m-d']);
        $this->allow(Siswa::findOrFail($d['siswa_id']));
        FollowUp::create($d + ['user_id' => auth()->id()]);
        SchoolContext::audit('followup.created', 'siswa:'.$d['siswa_id']);

        return back()->with('success', 'Tindak lanjut dicatat.');
    }

    public function close(FollowUp $follow)
    {
        $this->allow($follow->siswa);
        $follow->update(['status' => 'closed']);
        SchoolContext::audit('followup.closed', 'followup:'.$follow->id);

        return back()->with('success', 'Tindak lanjut selesai.');
    }
}
