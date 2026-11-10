<?php

namespace Tests\Feature;

use App\Models\AcademicPeriod;
use App\Models\Enrollment;
use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\Konseling;
use App\Models\Materi;
use App\Models\OrangTua;
use App\Models\Siswa;
use App\Models\User;
use App\Services\SchoolContext;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SchoolEdgeCasesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config(['app.key' => 'base64:'.base64_encode(str_repeat('a', 32))]);
        $this->seed();
        $j = Jadwal::first();
        AcademicPeriod::updateOrCreate(['tahun_ajaran' => $j->tahun_ajaran, 'semester' => $j->semester], ['active' => true]);
        foreach (Siswa::all() as $s) {
            Enrollment::create(['siswa_id' => $s->id, 'kelas_id' => $s->kelas_id, 'tahun_ajaran' => $j->tahun_ajaran]);
        }
    }

    private function admin()
    {
        return User::where('role', 'admin')->firstOrFail();
    }

    public function test_promotion_retains_history_and_rejects_past_year(): void
    {
        $s = Siswa::first();
        $source = $s->kelas_id;
        $target = Kelas::where('tingkat', 'XI')->first();
        $year = Jadwal::first()->tahun_ajaran;
        $this->actingAs($this->admin())->post(route('school.academic.promote'), ['source' => $source, 'target' => $target->id, 'tahun_ajaran' => $year, 'confirm' => 1])->assertSessionHasErrors('tahun_ajaran');
        $this->post(route('school.academic.promote'), ['source' => $source, 'target' => $target->id, 'tahun_ajaran' => '2027/2028', 'confirm' => 1])->assertSessionHasNoErrors();
        $this->assertSame($target->id, $s->fresh()->kelas_id);
        $this->assertDatabaseHas('enrollments', ['siswa_id' => $s->id, 'kelas_id' => $source, 'tahun_ajaran' => $year]);
        $this->assertDatabaseHas('enrollments', ['siswa_id' => $s->id, 'kelas_id' => $target->id, 'tahun_ajaran' => '2027/2028']);
        $j = Jadwal::first();
        $this->actingAs($j->guru->user)->get(route('guru.nilai.form', ['kelas_id' => $source, 'mata_pelajaran_id' => $j->mata_pelajaran_id, 'tahun_ajaran' => $year, 'semester' => $j->semester]))->assertOk()->assertSee($s->user->name);
    }

    public function test_capacity_failure_does_not_move_any_students(): void
    {
        $source = Kelas::first();
        $target = Kelas::skip(1)->first();
        $target->update(['kapasitas' => 1]);
        $before = Enrollment::count();
        $this->actingAs($this->admin())->post(route('school.academic.promote'), ['source' => $source->id, 'target' => $target->id, 'tahun_ajaran' => '2027/2028', 'confirm' => 1])->assertSessionHasErrors('target');
        $this->assertSame($before, Enrollment::count());
        $this->assertSame(0, $target->siswa()->count());
    }

    public function test_existing_parent_can_be_reused_by_manual_student_form(): void
    {
        $p = OrangTua::first();
        $before = OrangTua::count();
        $this->actingAs($this->admin())->post(route('admin.siswa.store'), ['name' => 'Saudara', 'email_siswa' => 'saudara@example.com', 'password_siswa' => 'validpassword', 'nis' => 'SIB001', 'kelas_id' => Kelas::first()->id, 'jenis_kelamin' => 'L', 'tahun_masuk' => '2026', 'orang_tua_id' => $p->id])->assertSessionHasNoErrors();
        $this->assertSame($before, OrangTua::count());
        $this->assertDatabaseHas('siswa', ['nis' => 'SIB001', 'orang_tua_id' => $p->id]);
    }

    public function test_other_teacher_cannot_delete_material_and_unrelated_student_cannot_download(): void
    {
        Storage::fake('local');
        $j = Jadwal::first();
        $m = Materi::create(['guru_id' => $j->guru_id, 'mata_pelajaran_id' => $j->mata_pelajaran_id, 'kelas_id' => $j->kelas_id, 'judul' => 'Privat', 'file_path' => 'materi/test.pdf', 'tahun_ajaran' => $j->tahun_ajaran, 'semester' => $j->semester]);
        Storage::disk('local')->put($m->file_path, 'test');
        $g = Guru::where('id', '!=', $j->guru_id)->whereHas('user', fn ($q) => $q->where('role', 'guru'))->first();
        $this->actingAs($g->user)->delete(route('guru.materi.destroy', $m))->assertForbidden();
        $s = Siswa::first();
        $s->update(['kelas_id' => Kelas::where('id', '!=', $j->kelas_id)->first()->id]);
        $this->actingAs($s->user)->get(route('school.material', $m))->assertForbidden();
        $this->actingAs($j->guru->user)->get(route('school.material', $m))->assertOk();
    }

    public function test_schedule_conflict_and_invalid_time_are_rejected(): void
    {
        $j = Jadwal::first();
        $d = $j->only(['kelas_id', 'mata_pelajaran_id', 'guru_id', 'hari', 'tahun_ajaran', 'semester']);
        $d += ['jam_mulai' => '08:00', 'jam_selesai' => '08:30'];
        $this->actingAs($this->admin())->post(route('admin.jadwal.store'), $d)->assertSessionHasErrors('jadwal');
        $d['jam_selesai'] = '07:00';
        $this->post(route('admin.jadwal.store'), $d)->assertSessionHasErrors('jam_selesai');
    }

    public function test_temporary_password_must_be_changed_before_dashboard(): void
    {
        $u = User::where('role', 'siswa')->first();
        $u->forceFill(['must_change_password' => true])->save();
        $this->actingAs($u)->get(route('siswa.dashboard'))->assertRedirect(route('profile.edit'));
        $this->get(route('profile.edit'))->assertOk();
        $this->put(route('profile.password'), ['current_password' => 'password', 'new_password' => 'a-new-password-123', 'new_password_confirmation' => 'a-new-password-123'])->assertSessionHasNoErrors();
        $this->assertFalse((bool) $u->fresh()->must_change_password);
        $this->get(route('siswa.dashboard'))->assertOk();
    }

    public function test_counselling_internal_note_is_not_visible_to_parent(): void
    {
        $k = Konseling::firstOrFail();
        $k->update(['catatan_bk' => 'RAHASIA INTERNAL 123', 'ringkasan_ortu' => 'Ringkasan pendampingan']);
        $this->actingAs($k->siswa->orangTua->user)->get(route('orang_tua.konseling'))->assertOk()->assertDontSee('RAHASIA INTERNAL 123')->assertSee('Ringkasan pendampingan');
        $u = User::create(['name' => 'BK lain', 'email' => 'bk2@example.com', 'password' => 'password', 'role' => 'guru_bk', 'is_active' => true]);
        $g = Guru::create(['user_id' => $u->id, 'jenis_kelamin' => 'L']);
        $k->update(['guru_bk_id' => Guru::where('id', '!=', $g->id)->whereHas('user', fn ($q) => $q->where('role', 'guru_bk'))->firstOrFail()->id]);
        $this->actingAs($u)->put(route('guru_bk.konseling.update', $k), ['status' => 'disetujui'])->assertForbidden();
    }

    public function test_new_period_replaces_old_active_period(): void
    {
        $this->actingAs($this->admin())->post(route('school.academic.period'), ['tahun_ajaran' => '2026/2027', 'semester' => '1'])->assertSessionHasNoErrors();
        $this->assertSame(1, AcademicPeriod::where('active', true)->count());
        $this->assertSame('2026/2027', SchoolContext::period()['tahun_ajaran']);
    }

    public function test_legacy_material_migration_verifies_copy_before_deleting_public_file(): void
    {
        Storage::fake('public');
        Storage::fake('local');
        $j = Jadwal::first();
        Materi::create(['guru_id' => $j->guru_id, 'mata_pelajaran_id' => $j->mata_pelajaran_id, 'kelas_id' => $j->kelas_id, 'judul' => 'Lama', 'file_path' => 'materi/old.pdf', 'tahun_ajaran' => $j->tahun_ajaran, 'semester' => $j->semester]);
        Storage::disk('public')->put('materi/old.pdf', 'original');
        $this->artisan('school:privatize-materials')->assertSuccessful();
        Storage::disk('public')->assertExists('materi/old.pdf');
        Storage::disk('local')->put('materi/old.pdf', 'different');
        $this->artisan('school:privatize-materials', ['--apply' => true])->assertFailed();
        Storage::disk('public')->assertExists('materi/old.pdf');
        Storage::disk('local')->delete('materi/old.pdf');
        $this->artisan('school:privatize-materials', ['--apply' => true])->assertSuccessful();
        $this->assertSame('original', Storage::disk('local')->get('materi/old.pdf'));
        Storage::disk('public')->assertMissing('materi/old.pdf');
    }

    public function test_student_profile_edit_preserves_prior_year_enrollment_after_promotion(): void
    {
        $s = Siswa::first();
        $oldClass = $s->kelas_id;
        $target = Kelas::where('tingkat', 'XI')->first();
        $year = SchoolContext::period()['tahun_ajaran'];
        $s->update(['kelas_id' => $target->id]);
        Enrollment::create(['siswa_id' => $s->id, 'kelas_id' => $target->id, 'tahun_ajaran' => '2027/2028']);
        $data = ['name' => 'Updated Name', 'kelas_id' => $target->id, 'jenis_kelamin' => $s->jenis_kelamin, 'status' => 'aktif'];
        $this->actingAs($this->admin())->put(route('admin.siswa.update', $s), $data)->assertSessionHasNoErrors();
        $this->assertDatabaseHas('enrollments', ['siswa_id' => $s->id, 'tahun_ajaran' => $year, 'kelas_id' => $oldClass]);
        $data['kelas_id'] = $oldClass;
        $this->put(route('admin.siswa.update', $s), $data)->assertSessionHasErrors('kelas_id');
        $this->assertSame($target->id, $s->fresh()->kelas_id);
    }
}
