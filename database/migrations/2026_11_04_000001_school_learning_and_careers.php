<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('school_settings', function (Blueprint $t) {
            $t->id(); $t->json('data'); $t->timestamps();
        });
        Schema::create('grade_schemes', function (Blueprint $t) {
            $t->id(); $t->foreignId('mata_pelajaran_id')->constrained('mata_pelajaran')->restrictOnDelete();
            $t->string('tahun_ajaran'); $t->string('semester'); $t->json('weights'); $t->timestamps();
            $t->unique(['mata_pelajaran_id', 'tahun_ajaran', 'semester']);
        });
        Schema::create('assignments', function (Blueprint $t) {
            $t->id(); $t->foreignId('jadwal_id')->constrained('jadwal')->restrictOnDelete();
            $t->foreignId('guru_id')->constrained('guru')->restrictOnDelete();
            $t->string('title'); $t->text('instructions'); $t->text('rubric')->nullable();
            $t->dateTime('due_at'); $t->string('file_path')->nullable(); $t->boolean('published')->default(false); $t->timestamps();
        });
        Schema::create('assignment_submissions', function (Blueprint $t) {
            $t->id(); $t->foreignId('assignment_id')->constrained('assignments')->restrictOnDelete();
            $t->foreignId('siswa_id')->constrained('siswa')->restrictOnDelete();
            $t->text('answer')->nullable(); $t->string('file_path')->nullable(); $t->dateTime('submitted_at');
            $t->decimal('score', 5, 2)->nullable(); $t->text('feedback')->nullable();
            $t->dateTime('reviewed_at')->nullable(); $t->foreignId('reviewed_by')->nullable()->constrained('users')->restrictOnDelete();
            $t->timestamps(); $t->unique(['assignment_id', 'siswa_id']);
        });
        Schema::create('industry_partners', function (Blueprint $t) {
            $t->id(); $t->string('name'); $t->string('sector')->nullable(); $t->string('contact_name');
            $t->string('phone', 30); $t->string('email')->nullable(); $t->text('address');
            $t->unsignedInteger('capacity')->default(1); $t->boolean('active')->default(true); $t->timestamps();
        });
        Schema::create('internship_placements', function (Blueprint $t) {
            $t->id(); $t->foreignId('siswa_id')->constrained('siswa')->restrictOnDelete();
            $t->foreignId('industry_partner_id')->constrained('industry_partners')->restrictOnDelete();
            $t->foreignId('guru_id')->constrained('guru')->restrictOnDelete();
            $t->string('mentor_name'); $t->string('mentor_phone', 30)->nullable();
            $t->date('starts_on'); $t->date('ends_on'); $t->string('status')->default('planned');
            $t->decimal('final_score', 5, 2)->nullable(); $t->text('review_notes')->nullable(); $t->timestamps();
            $t->index(['siswa_id', 'starts_on', 'ends_on']);
        });
        Schema::create('internship_journals', function (Blueprint $t) {
            $t->id(); $t->foreignId('internship_placement_id')->constrained('internship_placements')->restrictOnDelete();
            $t->date('date'); $t->decimal('hours', 4, 1); $t->text('activities'); $t->string('file_path')->nullable();
            $t->text('review_notes')->nullable(); $t->timestamp('approved_at')->nullable();
            $t->foreignId('reviewed_by')->nullable()->constrained('users')->restrictOnDelete();
            $t->timestamps(); $t->unique(['internship_placement_id', 'date']);
        });
        Schema::create('competency_exams', function (Blueprint $t) {
            $t->id(); $t->foreignId('kelas_id')->constrained('kelas')->restrictOnDelete();
            $t->foreignId('guru_id')->constrained('guru')->restrictOnDelete();
            $t->string('title'); $t->string('scheme'); $t->string('examiner'); $t->string('location');
            $t->string('tahun_ajaran'); $t->dateTime('scheduled_at'); $t->text('rubric');
            $t->decimal('pass_mark', 5, 2)->default(75); $t->string('status')->default('draft'); $t->timestamps();
        });
        Schema::create('competency_results', function (Blueprint $t) {
            $t->id(); $t->foreignId('competency_exam_id')->constrained('competency_exams')->restrictOnDelete();
            $t->foreignId('siswa_id')->constrained('siswa')->restrictOnDelete();
            $t->decimal('score', 5, 2)->nullable(); $t->text('feedback')->nullable();
            $t->string('certificate_path')->nullable(); $t->timestamps(); $t->unique(['competency_exam_id', 'siswa_id']);
        });
        Schema::create('portfolios', function (Blueprint $t) {
            $t->id(); $t->foreignId('siswa_id')->constrained('siswa')->restrictOnDelete();
            $t->string('title'); $t->string('category'); $t->text('description'); $t->string('url', 2000)->nullable();
            $t->string('file_path')->nullable(); $t->foreignId('verified_by')->nullable()->constrained('users')->restrictOnDelete();
            $t->timestamp('verified_at')->nullable(); $t->text('review_notes')->nullable(); $t->timestamps();
        });
        Schema::create('job_vacancies', function (Blueprint $t) {
            $t->id(); $t->foreignId('industry_partner_id')->constrained('industry_partners')->restrictOnDelete();
            $t->string('title'); $t->text('requirements'); $t->date('closes_on'); $t->boolean('active')->default(true); $t->timestamps();
        });
        Schema::create('job_applications', function (Blueprint $t) {
            $t->id(); $t->foreignId('job_vacancy_id')->constrained('job_vacancies')->restrictOnDelete();
            $t->foreignId('siswa_id')->constrained('siswa')->restrictOnDelete();
            $t->text('cover_letter'); $t->string('cv_path'); $t->string('status')->default('applied');
            $t->text('review_notes')->nullable(); $t->timestamps(); $t->unique(['job_vacancy_id', 'siswa_id']);
        });
        Schema::create('alumni_profiles', function (Blueprint $t) {
            $t->id(); $t->foreignId('siswa_id')->unique()->constrained('siswa')->restrictOnDelete();
            $t->unsignedSmallInteger('graduation_year'); $t->string('activity'); $t->string('organization')->nullable();
            $t->string('position')->nullable(); $t->date('started_on')->nullable(); $t->boolean('field_match')->nullable();
            $t->unsignedSmallInteger('waiting_months')->nullable(); $t->boolean('consent_contact')->default(false);
            $t->timestamp('verified_at')->nullable(); $t->timestamps();
        });
    }

    public function down(): void
    {
        foreach (['alumni_profiles', 'job_applications', 'job_vacancies', 'portfolios', 'competency_results', 'competency_exams', 'internship_journals', 'internship_placements', 'industry_partners', 'assignment_submissions', 'assignments', 'grade_schemes', 'school_settings'] as $table) Schema::dropIfExists($table);
    }
};
