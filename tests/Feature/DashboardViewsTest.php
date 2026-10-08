<?php

namespace Tests\Feature;

use App\Models\Absensi;
use App\Models\Berita;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardViewsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config(['app.key' => 'base64:'.base64_encode(str_repeat('a', 32))]);
        $this->seed();
    }

    public function test_each_role_can_render_its_dashboard_and_shared_navigation(): void
    {
        Absensi::query()->firstOrFail()->update(['tanggal' => today()]);

        foreach (['admin', 'guru', 'guru_bk', 'siswa', 'orang_tua'] as $role) {
            $user = User::where('role', $role)->firstOrFail();
            $response = $this->actingAs($user)->get(route($role.'.dashboard'));
            $response->assertOk()
                ->assertSee('id="school-sidebar"', false)
                ->assertSee('id="dashboard-content"', false)
                ->assertSee('css/dashboard.css')
                ->assertSee('js/dashboard.js')
                ->assertSee('dashboard-welcome');

            // Optional local browser fixtures, generated from real Blade responses.
            if ($directory = getenv('DASHBOARD_QA_DIR')) {
                if (! is_dir($directory)) {
                    mkdir($directory, 0775, true);
                }
                file_put_contents($directory.'/'.$role.'.html', $response->getContent());
            }
        }
    }

    public function test_admin_dashboard_handles_empty_attendance_and_news(): void
    {
        Absensi::query()->delete();
        Berita::query()->delete();
        $this->actingAs(User::where('role', 'admin')->firstOrFail())
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Belum ada absensi hari ini.')
            ->assertSee('Belum ada berita dipublikasikan.')
            ->assertDontSee('id="absensiChart"', false);
    }

    public function test_parent_without_a_child_can_still_render_the_shared_layout(): void
    {
        $user = User::where('role', 'orang_tua')->firstOrFail();
        $user->orangTua->siswa()->update(['orang_tua_id' => null]);
        $this->actingAs($user)->get(route('orang_tua.dashboard'))
            ->assertOk()->assertSee('Data siswa belum tersedia.')
            ->assertDontSee('id="nilaiChart"', false);
    }

    public function test_student_cannot_access_the_admin_dashboard(): void
    {
        $this->actingAs(User::where('role', 'siswa')->firstOrFail())
            ->get(route('admin.dashboard'))->assertForbidden();
    }
}
