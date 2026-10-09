<?php

namespace Tests\Feature;

use App\Models\Absensi;
use App\Models\Enrollment;
use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\Nilai;
use App\Models\Notifikasi;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AcademicIntegrityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config(['app.key' => 'base64:'.base64_encode(str_repeat('a', 32))]);
        $this->seed();
        foreach (Siswa::all() as $s) {
            Enrollment::create(['siswa_id' => $s->id, 'kelas_id' => $s->kelas_id, 'tahun_ajaran' => Jadwal::first()->tahun_ajaran]);
        }
    }

    private function attendance(): array
    {
        $j = Jadwal::firstOrFail();
        $s = Siswa::where('kelas_id', $j->kelas_id)->firstOrFail();

        return [$j, $s, ['jadwal_id' => $j->id, 'tanggal' => '2026-10-08', 'absensi' => [$s->id => ['status' => 'Izin']]]];
    }

    public function test_other_teacher_cannot_read_or_write_attendance(): void
    {
        [$j,$s,$data] = $this->attendance();
        $u = Guru::where('id', '!=', $j->guru_id)->whereHas('user', fn ($q) => $q->where('role', 'guru'))->firstOrFail()->user;
        $this->actingAs($u)->get(route('guru.absensi.form', $j))->assertForbidden();
        $this->post(route('guru.absensi.store'), $data)->assertForbidden();
    }

    public function test_absence_notification_is_not_duplicated_on_resave(): void
    {
        [$j,$s,$data] = $this->attendance();
        $this->actingAs($j->guru->user)->post(route('guru.absensi.store'), $data)->assertRedirect();
        $count = Notifikasi::count();
        $this->post(route('guru.absensi.store'), $data)->assertRedirect();
        $this->assertSame($count, Notifikasi::count());
        $this->assertSame(1, Absensi::where('jadwal_id', $j->id)->where('siswa_id', $s->id)->whereDate('tanggal', '2026-10-08')->count());
    }

    public function test_invalid_status_rejects_entire_submission(): void
    {
        [$j,$s,$data] = $this->attendance();
        $data['absensi'][$s->id]['status'] = 'Invalid';
        $before = Absensi::count();
        $this->actingAs($j->guru->user)->post(route('guru.absensi.store'), $data)->assertSessionHasErrors();
        $this->assertSame($before, Absensi::count());
    }

    public function test_zero_grade_is_calculated_and_out_of_range_rejected(): void
    {
        $j = Jadwal::firstOrFail();
        $s = Siswa::where('kelas_id', $j->kelas_id)->firstOrFail();
        $data = ['kelas_id' => $j->kelas_id, 'mata_pelajaran_id' => $j->mata_pelajaran_id, 'tahun_ajaran' => $j->tahun_ajaran, 'semester' => $j->semester, 'nilai' => [$s->id => ['nilai_harian' => 0, 'nilai_uts' => 80, 'nilai_uas' => 80]]];
        $this->actingAs($j->guru->user)->post(route('guru.nilai.store'), $data)->assertSessionHasNoErrors();
        $n = Nilai::where('siswa_id', $s->id)->where('mata_pelajaran_id', $j->mata_pelajaran_id)->where('tahun_ajaran', $j->tahun_ajaran)->where('semester', $j->semester)->firstOrFail();
        $this->assertEquals(48, $n->nilai_akhir);
        $data['nilai'][$s->id]['nilai_harian'] = 101;
        $this->post(route('guru.nilai.store'), $data)->assertSessionHasErrors();
        $this->assertEquals(48, $n->fresh()->nilai_akhir);
    }

    public function test_deleting_schedule_with_history_is_blocked(): void
    {
        $j = Absensi::firstOrFail()->jadwal;
        $this->actingAs(User::where('role', 'admin')->firstOrFail())->delete(route('admin.jadwal.destroy', $j))->assertSessionHasErrors('hapus');
        $this->assertModelExists($j);
    }
}
