<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PerihalSurat;

class PerihalSuratSeeder extends Seeder
{
    public function run(): void
    {
        if (PerihalSurat::count() == 0) {
            PerihalSurat::insert([
                [
                    'nama_perihal'  => 'Undangan Kegiatan',
                    'jenis_surat'   => 'undangan',
                    'template_view' => 'templates.undangan',
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ],
                [
                    'nama_perihal'  => 'Peminjaman Fasilitas',
                    'jenis_surat'   => 'peminjaman',
                    'template_view' => 'templates.peminjaman',
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ],
            ]);

            $this->command->info('Perihal Surat berhasil dibuat');
        } else {
            $this->command->info('Perihal Surat sudah ada');
        }
    }
}

