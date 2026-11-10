<?php

namespace Tests\Feature;

use App\Models\{Guru, Jadwal, Nilai, Siswa, User};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurityValidationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_teacher_cannot_open_another_teachers_attendance_form(): void
    {
        $teacher = User::where('email', 'dewi@smartschool.id')->firstOrFail();
        $otherTeacherSchedule = Jadwal::firstOrFail();

        $this->actingAs($teacher)
            ->get(route('guru.absensi.form', $otherTeacherSchedule))
            ->assertForbidden();
    }

    public function test_teacher_cannot_submit_attendance_for_another_teacher(): void
    {
        $teacher = User::where('email', 'dewi@smartschool.id')->firstOrFail();
        $schedule = Jadwal::firstOrFail();
        $student = Siswa::firstOrFail();

        $this->actingAs($teacher)->post(route('guru.absensi.store'), [
            'jadwal_id' => $schedule->id,
            'tanggal' => today()->toDateString(),
            'absensi' => [$student->id => ['status' => 'Hadir']],
        ])->assertNotFound();
    }

    public function test_invalid_attendance_status_is_rejected(): void
    {
        $teacher = User::where('email', 'guru@smartschool.id')->firstOrFail();
        $schedule = Jadwal::firstOrFail();
        $student = Siswa::firstOrFail();

        $this->actingAs($teacher)->post(route('guru.absensi.store'), [
            'jadwal_id' => $schedule->id,
            'tanggal' => today()->toDateString(),
            'absensi' => [$student->id => ['status' => 'Unknown']],
        ])->assertSessionHasErrors('absensi.'.$student->id.'.status');
    }

    public function test_zero_grades_are_calculated_instead_of_treated_as_missing(): void
    {
        $teacher = User::where('email', 'guru@smartschool.id')->firstOrFail();
        $schedule = Jadwal::firstOrFail();
        $student = Siswa::firstOrFail();

        $this->actingAs($teacher)->post(route('guru.nilai.store'), [
            'kelas_id' => $schedule->kelas_id,
            'mata_pelajaran_id' => $schedule->mata_pelajaran_id,
            'tahun_ajaran' => $schedule->tahun_ajaran,
            'semester' => $schedule->semester,
            'nilai' => [$student->id => [
                'nilai_harian' => 0,
                'nilai_uts' => 0,
                'nilai_uas' => 0,
            ]],
        ])->assertSessionHasNoErrors();

        $nilai = Nilai::where('siswa_id', $student->id)
            ->where('mata_pelajaran_id', $schedule->mata_pelajaran_id)
            ->where('tahun_ajaran', $schedule->tahun_ajaran)
            ->where('semester', $schedule->semester)->firstOrFail();

        $this->assertEquals(0, $nilai->nilai_akhir);
        $this->assertSame('E', $nilai->predikat);
    }

    public function test_teacher_cannot_delete_another_teachers_material(): void
    {
        $teacher = User::where('email', 'dewi@smartschool.id')->firstOrFail();
        $materi = \App\Models\Materi::firstOrFail();

        $this->actingAs($teacher)
            ->delete(route('guru.materi.destroy', $materi))
            ->assertForbidden();
    }
}
