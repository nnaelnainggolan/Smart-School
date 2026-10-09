<?php

namespace App\Console\Commands;

use App\Models\Materi;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class PrivatizeMaterials extends Command
{
    protected $signature = 'school:privatize-materials {--apply : Salin terverifikasi lalu hapus salinan publik}';

    protected $description = 'Pratinjau/pindahkan lampiran materi lama ke penyimpanan privat';

    public function handle(): int
    {
        $public = Storage::disk('public');
        $private = Storage::disk('local');
        foreach (Materi::whereNotNull('file_path')->pluck('file_path')->unique() as $path) {
            if (! str_starts_with($path, 'materi/') || str_contains($path, '..')) {
                $this->error('Path materi tidak valid; periksa database.');

                return self::FAILURE;
            }
            if (! $public->exists($path)) {
                continue;
            }
            $this->line($path);
            if (! $this->option('apply')) {
                continue;
            }
            $contents = $public->get($path);
            if (! $private->exists($path) && ! $private->put($path, $contents)) {
                $this->error('Gagal menyalin; salinan publik tetap disimpan.');

                return self::FAILURE;
            }
            if (! hash_equals(hash('sha256', $contents), hash('sha256', $private->get($path)))) {
                $this->error('Isi privat berbeda; salinan publik tidak dihapus.');

                return self::FAILURE;
            }
            if (! $public->delete($path)) {
                $this->error('Gagal menghapus salinan publik.');

                return self::FAILURE;
            }
        }
        $this->info($this->option('apply') ? 'Pemindahan selesai.' : 'Pratinjau selesai; gunakan --apply setelah mencadangkan storage.');

        return self::SUCCESS;
    }
}
