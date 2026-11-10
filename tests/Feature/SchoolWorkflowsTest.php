<?php

namespace Tests\Feature;

use App\Models\AcademicPeriod;
use App\Models\Admission;
use App\Models\Enrollment;
use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\LeaveRequest;
use App\Models\Nilai;
use App\Models\Notifikasi;
use App\Models\ReportPublication;
use App\Models\SchoolImport;
use App\Models\Siswa;
use App\Models\User;
use App\Services\StudentImport;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class SchoolWorkflowsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config(['app.key' => 'base64:'.base64_encode(str_repeat('a', 32))]);
        $this->seed();
        $j = Jadwal::firstOrFail();
        AcademicPeriod::create(['tahun_ajaran' => $j->tahun_ajaran, 'semester' => $j->semester, 'active' => true]);
        foreach (Siswa::all() as $s) {
            Enrollment::create(['siswa_id' => $s->id, 'kelas_id' => $s->kelas_id, 'tahun_ajaran' => $j->tahun_ajaran]);
        }
        Kelas::first()->update(['wali_guru_id' => $j->guru_id]);
    }

    private function admin()
    {
        return User::where('role', 'admin')->firstOrFail();
    }

    private function rows()
    {
        return [
            ['nis' => '9001', 'nama_siswa' => 'Anak Satu', 'email_siswa' => 'anak1@example.com', 'jenis_kelamin' => 'L', 'kelas' => Kelas::first()->nama_kelas, 'tahun_masuk' => '2026', 'nama_ortu' => 'Wali Bersama', 'email_ortu' => 'wali@example.com', 'no_hp_ortu' => '08123456789'],
            ['nis' => '9002', 'nama_siswa' => 'Anak Dua', 'email_siswa' => 'anak2@example.com', 'jenis_kelamin' => 'P', 'kelas' => Kelas::first()->nama_kelas, 'tahun_masuk' => '2026', 'nama_ortu' => 'Wali Bersama', 'email_ortu' => 'wali@example.com', 'no_hp_ortu' => '08123456789'],
        ];
    }

    public function test_import_preview_commit_creates_one_parent_and_cannot_replay(): void
    {
        $this->actingAs($this->admin());
        $rows = $this->rows();
        $csv = implode(',', StudentImport::COLUMNS)."\n";
        foreach ($rows as $row) {
            $csv .= implode(',', $row)."\n";
        }
        $before = Siswa::count();
        $this->post(route('school.import.preview'), ['file' => UploadedFile::fake()->createWithContent('data.csv', $csv)])->assertOk()->assertSee('Pratinjau');
        $this->assertSame($before, Siswa::count());
        $batch = SchoolImport::latest('id')->firstOrFail();
        $this->post(route('school.import.commit', $batch))->assertDownload('akun-baru.csv');
        $a = Siswa::where('nis', '9001')->firstOrFail();
        $b = Siswa::where('nis', '9002')->firstOrFail();
        $this->assertSame($a->orang_tua_id, $b->orang_tua_id);
        $this->assertTrue((bool) $a->user->must_change_password);
        $this->assertSame(1, User::where('email', 'wali@example.com')->count());
        $this->post(route('school.import.commit', $batch))->assertStatus(409);
    }

    public function test_import_revalidates_after_preview_and_rolls_back(): void
    {
        $rows = $this->rows();
        $batch = SchoolImport::create(['user_id' => $this->admin()->id, 'payload' => $rows]);
        User::create(['name' => 'Collision', 'email' => 'anak2@example.com', 'password' => 'password', 'role' => 'siswa']);
        $this->actingAs($this->admin())->post(route('school.import.commit', $batch))->assertSessionHasErrors('file');
        $this->assertDatabaseMissing('siswa', ['nis' => '9001']);
        $this->assertNull($batch->fresh()->consumed_at);
    }

    public function test_parent_can_switch_children_but_not_another_family(): void
    {
        $s = Siswa::first();
        $sib = Siswa::skip(1)->first();
        $sib->update(['orang_tua_id' => $s->orang_tua_id]);
        $other = Siswa::skip(2)->first();
        $this->actingAs($s->orangTua->user)->get(route('orang_tua.dashboard', ['anak' => $sib->id]))->assertOk()->assertSee($sib->user->name);
        $this->get(route('orang_tua.dashboard', ['anak' => $other->id]))->assertNotFound();
    }

    public function test_parent_without_children_redirects_safely(): void
    {
        $s = Siswa::first();
        $u = $s->orangTua->user;
        $s->update(['orang_tua_id' => null]);
        $this->actingAs($u)->get(route('orang_tua.absensi'))->assertRedirect(route('orang_tua.dashboard'));
    }

    public function test_leave_only_owner_can_submit_and_homeroom_can_review(): void
    {
        $s = Siswa::first();
        $other = Siswa::skip(1)->first();
        $data = ['siswa_id' => $s->id, 'start_date' => '2026-10-08', 'end_date' => '2026-10-09', 'type' => 'Sakit', 'reason' => 'Istirahat'];
        $this->actingAs($other->orangTua->user)->post(route('school.leave.store'), $data)->assertNotFound();
        $this->actingAs($s->orangTua->user)->post(route('school.leave.store'), $data)->assertSessionHasNoErrors();
        $l = LeaveRequest::firstOrFail();
        $guru = Guru::findOrFail($s->kelas->wali_guru_id);
        $this->actingAs($guru->user)->put(route('school.leave.review', $l), ['status' => 'approved', 'review_note' => 'Diterima'])->assertSessionHasNoErrors();
        $this->assertSame('approved', $l->fresh()->status);
        $this->put(route('school.leave.review', $l), ['status' => 'rejected', 'review_note' => 'Ulang'])->assertStatus(409);
    }

    public function test_published_report_locks_grades_and_other_parent_cannot_read(): void
    {
        $s = Siswa::first();
        $j = Jadwal::first();
        $key = ['siswa_id' => $s->id, 'tahun_ajaran' => $j->tahun_ajaran, 'semester' => $j->semester];
        foreach (Jadwal::where('kelas_id', $s->kelas_id)->get()->unique('mata_pelajaran_id') as $schedule) {
            Nilai::updateOrCreate($key + ['mata_pelajaran_id' => $schedule->mata_pelajaran_id], ['guru_id' => $schedule->guru_id, 'nilai_harian' => 80, 'nilai_uts' => 80, 'nilai_uas' => 80, 'nilai_akhir' => 80, 'predikat' => 'B']);
        }
        $this->actingAs($this->admin())->post(route('school.reports.publish'), $key + ['confirm' => 1])->assertSessionHasNoErrors();
        $pub = ReportPublication::where($key)->firstOrFail();
        $this->assertNotNull($pub->published_at);
        $this->actingAs($j->guru->user)->post(route('guru.nilai.store'), ['kelas_id' => $s->kelas_id, 'mata_pelajaran_id' => $j->mata_pelajaran_id, 'tahun_ajaran' => $j->tahun_ajaran, 'semester' => $j->semester, 'nilai' => [$s->id => ['nilai_harian' => 90, 'nilai_uts' => 90, 'nilai_uas' => 90]]])->assertSessionHasErrors('nilai');
        $this->actingAs($s->orangTua->user)->get(route('school.reports.show', $pub))->assertOk();
        $this->actingAs(Siswa::skip(1)->first()->orangTua->user)->get(route('school.reports.show', $pub))->assertNotFound();
        $this->actingAs($this->admin())->put(route('school.reports.reopen', $pub), ['reason' => 'Koreksi'])->assertSessionHasNoErrors();
        $this->assertNull($pub->fresh()->published_at);
    }

    public function test_ppdb_persists_and_converts_only_once(): void
    {
        $this->post(route('ppdb.store'), ['name' => 'Calon Siswa', 'parent_name' => 'Wali Baru', 'email' => 'ppdb@example.com', 'phone' => '08123456', 'previous_school' => 'SMP Contoh', 'consent' => 1])->assertRedirect(route('ppdb.form'));
        $a = Admission::firstOrFail();
        $this->assertSame('pending', $a->status);
        $this->post(route('ppdb.check'), ['reference' => $a->reference, 'email' => $a->email])->assertOk()->assertSee('pending');
        $this->actingAs($this->admin())->put(route('school.admissions.review', $a), ['status' => 'accepted', 'note' => 'Berkas sesuai'])->assertSessionHasNoErrors();
        $d = ['nis' => 'PPDB001', 'email_siswa' => 'ppdb-siswa@example.com', 'jenis_kelamin' => 'L', 'kelas' => Kelas::first()->nama_kelas, 'tahun_masuk' => '2026'];
        $this->post(route('school.admissions.convert', $a), $d)->assertDownload('akun-ppdb.csv');
        $this->assertNotNull($a->fresh()->siswa_id);
        $this->post(route('school.admissions.convert', $a), $d)->assertStatus(409);
    }

    public function test_school_pages_render_for_each_role(): void
    {
        foreach (['admin', 'guru', 'guru_bk', 'siswa', 'orang_tua'] as $role) {
            $this->actingAs(User::where('role', $role)->firstOrFail())->get(route('school.hub'))->assertOk();
            if (in_array($role, ['admin', 'guru', 'orang_tua'])) {
                $this->get(route('school.leave'))->assertOk();
            }
            if (in_array($role, ['admin', 'guru', 'guru_bk'])) {
                $this->get(route('school.homeroom'))->assertOk();
            }
            if ($role !== 'guru_bk') {
                $this->get(route('school.reports'))->assertOk();
            }
        }
        $this->actingAs($this->admin());
        foreach (['school.academic', 'school.import', 'school.admissions'] as $route) {
            $this->get(route($route))->assertOk();
        }
    }

    public function test_only_owner_can_mark_notification_read(): void
    {
        $n = Notifikasi::firstOrFail();
        $other = User::where('id', '!=', $n->user_id)->where('role', 'admin')->firstOrFail();
        $this->actingAs($other)->put(route('school.notifications.read', $n))->assertForbidden();
        $this->actingAs(User::findOrFail($n->user_id))->put(route('school.notifications.read', $n))->assertRedirect();
        $this->assertEquals(1, $n->fresh()->dibaca);
    }
}
