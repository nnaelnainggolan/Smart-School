<?php

use App\Models\SchoolImport;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('school:prune-imports', function () {
    $count = SchoolImport::where('created_at', '<', now()->subDay())->delete();
    $this->info("{$count} pratinjau kedaluwarsa dihapus.");
})->purpose('Hapus payload impor kedaluwarsa');

Schedule::command('school:prune-imports')->daily();
