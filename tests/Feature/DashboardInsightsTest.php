<?php

namespace Tests\Feature;

use App\Models\Absensi;
use App\Models\AcademicPeriod;
use App\Models\Konseling;
use App\Models\Nilai;
use App\Models\User;
use App\Services\DashboardInsights;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardInsightsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config(['app.key' => 'base64:'.base64_encode(str_repeat('a', 32))]);
        $this->seed();
    }

    public function test_grades_use_active_period_preserve_zero_and_exclude_other_students(): void
    {
        $student = User::where('role', 'siswa')->first();
        $grade = Nilai::where('siswa_id', $student->siswa->id)->firstOrFail();
        AcademicPeriod::query()->update(['active' => false]);
        AcademicPeriod::updateOrCreate(['tahun_ajaran' => $grade->tahun_ajaran, 'semester' => $grade->semester], ['active' => true]);
        Nilai::where('siswa_id', $student->siswa->id)->where('id', '!=', $grade->id)->delete();
        $grade->update(['nilai_akhir' => 0]);
        $charts = app(DashboardInsights::class)->forUser($student);
        $this->assertTrue($charts[1]['hasData']);
        $this->assertCount(1, $charts[1]['rows']);
        $this->assertSame(0.0, $charts[1]['rows'][0]['value']);
        $this->actingAs($student)->get(route('siswa.dashboard'))->assertOk()->assertSee('0 poin')->assertSee('0.0');
        $grade->update(['nilai_akhir' => null]);
        $this->assertFalse(app(DashboardInsights::class)->forUser($student)[1]['hasData']);
        $grade->update(['semester' => $grade->semester === '1' ? '2' : '1', 'nilai_akhir' => 99]);
        $this->assertCount(0, app(DashboardInsights::class)->forUser($student)[1]['rows']);
    }

    public function test_attendance_distinguishes_missing_days_from_zero_percent(): void
    {
        $student = User::where('role', 'siswa')->first();
        $record = Absensi::where('siswa_id', $student->siswa->id)->firstOrFail();
        Absensi::where('siswa_id', $student->siswa->id)->where('id', '!=', $record->id)->delete();
        $record->update(['tanggal' => today(), 'status' => 'Alpha']);
        $chart = app(DashboardInsights::class)->forUser($student)[0];
        $this->assertTrue($chart['hasData']);
        $this->assertNull($chart['rows'][5]['value']);
        $this->assertEquals(0, $chart['rows'][6]['value']);
        $this->assertSame('1 rekaman', $chart['rows'][6]['note']);
    }

    public function test_bk_charts_only_count_assigned_sessions(): void
    {
        $bk = User::where('role', 'guru_bk')->first();
        $assigned = Konseling::where('guru_bk_id', $bk->guru->id)->count();
        $charts = app(DashboardInsights::class)->forUser($bk);
        $this->assertSame($assigned, array_sum(array_column($charts[1]['rows'], 'value')));
        $this->assertGreaterThan($assigned, Konseling::count());
    }

    public function test_parent_charts_follow_selected_child_and_teacher_data_is_scoped(): void
    {
        $parent = User::where('role', 'orang_tua')->first();
        $child = $parent->orangTua->siswa()->first();
        $this->actingAs($parent)->get(route('orang_tua.dashboard', ['anak' => $child->id]))->assertOk()->assertViewHas('insights', function ($charts) use ($child) {
            return $charts === app(DashboardInsights::class)->forUser(auth()->user(), $child);
        });
        $teacher = User::where('role', 'guru')->first();
        $record = Absensi::where('guru_id', $teacher->guru->id)->first();
        $record->update(['tanggal' => today()]);
        $chart = app(DashboardInsights::class)->forUser($teacher)[0];
        $count = Absensi::where('guru_id', $teacher->guru->id)->whereDate('tanggal', today())->count();
        $this->assertSame($count.' rekaman', $chart['rows'][6]['note']);
    }
}
