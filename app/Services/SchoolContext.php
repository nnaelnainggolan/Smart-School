<?php

namespace App\Services;

use App\Models\AcademicPeriod;
use App\Models\Nilai;
use App\Models\SchoolAudit;
use App\Models\Siswa;
use Illuminate\Http\Request;

class SchoolContext
{
    public static function period(): array
    {
        $p = AcademicPeriod::where('active', true)->first();
        $year = now()->month >= 7 ? now()->year : now()->year - 1;

        return ['tahun_ajaran' => $p?->tahun_ajaran ?? "$year/".($year + 1), 'semester' => $p?->semester ?? (now()->month >= 7 ? '1' : '2')];
    }

    public static function years(): array
    {
        return AcademicPeriod::pluck('tahun_ajaran')->merge(Nilai::distinct()->pluck('tahun_ajaran'))->push(self::period()['tahun_ajaran'])->unique()->sortDesc()->values()->all();
    }

    public static function child(Request $request): ?Siswa
    {
        $parent = $request->user()->orangTua;
        abort_unless($parent, 403);
        $query = $parent->siswa()->with(['user', 'kelas']);
        if ($request->filled('anak')) {
            $child = (clone $query)->findOrFail($request->integer('anak'));
            $request->session()->put('selected_child', $child->id);

            return $child;
        }

        return (clone $query)->find($request->session()->get('selected_child')) ?? $query->first();
    }

    public static function audit(string $action, string $subject, array $detail = []): void
    {
        SchoolAudit::create(['user_id' => auth()->id(), 'action' => $action, 'subject' => $subject, 'detail' => $detail]);
    }
}
