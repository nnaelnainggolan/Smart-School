<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', fn (Blueprint $t) => $t->boolean('must_change_password')->default(false));
        Schema::create('academic_periods', function (Blueprint $t) {
            $t->id();
            $t->string('tahun_ajaran');
            $t->string('semester');
            $t->boolean('active')->default(false);
            $t->timestamps();
            $t->unique(['tahun_ajaran', 'semester']);
        });
        // Preserve the latest period already used by the school; do not relabel old data.
        $period = DB::table('jadwal')->orderByDesc('tahun_ajaran')->orderByDesc('semester')->first();
        if ($period) {
            DB::table('academic_periods')->insert(['tahun_ajaran' => $period->tahun_ajaran, 'semester' => $period->semester, 'active' => true, 'created_at' => now(), 'updated_at' => now()]);
        }
        Schema::table('kelas', fn (Blueprint $t) => $t->foreignId('wali_guru_id')->nullable()->constrained('guru')->restrictOnDelete());
        Schema::create('enrollments', function (Blueprint $t) {
            $t->id();
            $t->foreignId('siswa_id')->constrained('siswa')->restrictOnDelete();
            $t->foreignId('kelas_id')->constrained('kelas')->restrictOnDelete();
            $t->string('tahun_ajaran');
            $t->timestamps();
            $t->unique(['siswa_id', 'tahun_ajaran']);
        });
        if ($period) {
            foreach (DB::table('siswa')->whereNotNull('kelas_id')->get() as $s) {
                DB::table('enrollments')->insert(['siswa_id' => $s->id, 'kelas_id' => $s->kelas_id, 'tahun_ajaran' => $period->tahun_ajaran, 'created_at' => now(), 'updated_at' => now()]);
            }
        }
        Schema::create('school_imports', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $t->longText('payload');
            $t->timestamp('consumed_at')->nullable();
            $t->timestamps();
        });
        Schema::create('leave_requests', function (Blueprint $t) {
            $t->id();
            $t->foreignId('siswa_id')->constrained('siswa')->restrictOnDelete();
            $t->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $t->date('start_date');
            $t->date('end_date');
            $t->string('type');
            $t->text('reason');
            $t->string('attachment')->nullable();
            $t->string('status')->default('pending');
            $t->foreignId('reviewer_id')->nullable()->constrained('users')->restrictOnDelete();
            $t->text('review_note')->nullable();
            $t->timestamps();
        });
        Schema::create('report_publications', function (Blueprint $t) {
            $t->id();
            $t->foreignId('siswa_id')->constrained('siswa')->restrictOnDelete();
            $t->string('tahun_ajaran');
            $t->string('semester');
            $t->longText('snapshot');
            $t->foreignId('published_by')->nullable()->constrained('users')->restrictOnDelete();
            $t->timestamp('published_at')->nullable();
            $t->unsignedInteger('revision')->default(0);
            $t->timestamps();
            $t->unique(['siswa_id', 'tahun_ajaran', 'semester']);
        });
        Schema::create('follow_ups', function (Blueprint $t) {
            $t->id();
            $t->foreignId('siswa_id')->constrained('siswa')->restrictOnDelete();
            $t->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $t->text('note');
            $t->date('due_date')->nullable();
            $t->string('status')->default('open');
            $t->timestamps();
        });
        Schema::create('school_audits', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $t->string('action');
            $t->string('subject');
            $t->longText('detail')->nullable();
            $t->timestamps();
        });
        Schema::create('admissions', function (Blueprint $t) {
            $t->id();
            $t->uuid('reference')->unique();
            $t->string('name');
            $t->string('parent_name');
            $t->string('email');
            $t->string('phone');
            $t->string('previous_school');
            $t->string('document')->nullable();
            $t->string('status')->default('pending');
            $t->text('note')->nullable();
            $t->foreignId('siswa_id')->nullable()->constrained('siswa')->restrictOnDelete();
            $t->timestamps();
        });
        Schema::table('konseling', fn (Blueprint $t) => $t->text('ringkasan_ortu')->nullable());
    }

    public function down(): void
    {
        Schema::table('users', fn (Blueprint $t) => $t->dropColumn('must_change_password'));
        Schema::table('konseling', fn (Blueprint $t) => $t->dropColumn('ringkasan_ortu'));
        foreach (['admissions', 'school_audits', 'follow_ups', 'report_publications', 'leave_requests', 'school_imports', 'enrollments'] as $name) {
            Schema::dropIfExists($name);
        }
        Schema::table('kelas', fn (Blueprint $t) => $t->dropConstrainedForeignId('wali_guru_id'));
        Schema::dropIfExists('academic_periods');
    }
};
