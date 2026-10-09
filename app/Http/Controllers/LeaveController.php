<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Models\Siswa;
use App\Services\HomeroomAccess;
use App\Services\SchoolContext;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class LeaveController extends Controller
{
    private function query()
    {
        $q = LeaveRequest::with('siswa.user');
        $u = auth()->user();
        if ($u->role === 'orang_tua') {
            $q->whereIn('siswa_id', $u->orangTua?->siswa()->pluck('id') ?? []);
        } elseif ($u->role === 'guru') {
            $q->whereIn('siswa_id', Siswa::whereIn('kelas_id', HomeroomAccess::classes()->pluck('id'))->pluck('id'));
        } else {
            abort_unless($u->role === 'admin', 403);
        }

        return $q;
    }

    public function index()
    {
        $children = auth()->user()->role === 'orang_tua' ? auth()->user()->orangTua?->siswa()->with('user')->get() : collect();

        return view('school.page', ['title' => 'Pengajuan Izin', 'description' => 'Izin disetujui menjadi saran status pada form absensi. Guru tetap memeriksa dan menyimpan kehadiran tiap pelajaran.', 'body' => 'school.leave', 'leaves' => $this->query()->latest()->paginate(20), 'children' => $children ?? collect()]);
    }

    public function store(Request $r)
    {
        abort_unless($r->user()->role === 'orang_tua', 403);
        $d = $r->validate(['siswa_id' => 'required|integer', 'start_date' => 'required|date_format:Y-m-d', 'end_date' => 'required|date_format:Y-m-d|after_or_equal:start_date', 'type' => 'required|in:Izin,Sakit', 'reason' => 'required|string|max:2000', 'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120']);
        $r->user()->orangTua->siswa()->findOrFail($d['siswa_id']);
        unset($d['attachment']);
        $d['user_id'] = $r->user()->id;
        if ($r->hasFile('attachment')) {
            $d['attachment'] = $r->file('attachment')->store('izin', 'local');
        }
        try {
            LeaveRequest::create($d);
        } catch (\Throwable $e) {
            if (isset($d['attachment'])) {
                Storage::disk('local')->delete($d['attachment']);
            }throw $e;
        }

        return back()->with('success', 'Izin terkirim untuk diperiksa wali kelas.');
    }

    public function review(Request $r, LeaveRequest $leave)
    {
        abort_unless(in_array($r->user()->role, ['admin', 'guru']), 403);
        HomeroomAccess::student($leave->siswa);
        $d = $r->validate(['status' => 'required|in:approved,rejected', 'review_note' => 'required|string|max:1000']);
        DB::transaction(function () use ($d, $leave, $r) {
            $locked = LeaveRequest::lockForUpdate()->findOrFail($leave->id);
            abort_unless($locked->status === 'pending', 409);
            $locked->update($d + ['reviewer_id' => $r->user()->id]);
            SchoolContext::audit('leave.reviewed', 'leave:'.$leave->id, ['status' => $d['status']]);
        });

        return back()->with('success', 'Keputusan izin tersimpan.');
    }

    public function attachment(LeaveRequest $leave)
    {
        $this->query()->findOrFail($leave->id);
        abort_unless($leave->attachment, 404);

        return Storage::disk('local')->download($leave->attachment, null, ['Cache-Control' => 'private, no-store']);
    }
}
