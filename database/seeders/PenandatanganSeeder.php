<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Penandatangan;

class PenandatanganSeeder extends Seeder
{
    public function run(): void
    {
        if (Penandatangan::count() == 0) {
            Penandatangan::insert([
                [
                    'nama_penandatangan'    => 'Ketua Umum',
                    'nip_npm_penandatangan' => null,
                    'jabatan_penandatangan' => 'Ketua Umum',
                    'gambar_tandatangan'    => null,
                    'created_at'            => now(),
                    'updated_at'            => now(),
                ],
                [
                    'nama_penandatangan'    => 'Sekretaris Umum',
                    'nip_npm_penandatangan' => null,
                    'jabatan_penandatangan' => 'Sekretaris Umum',
                    'gambar_tandatangan'    => null,
                    'created_at'            => now(),
                    'updated_at'            => now(),
                ],
                [
                    'nama_penandatangan'    => 'Bendahara Umum',
                    'nip_npm_penandatangan' => null,
                    'jabatan_penandatangan' => 'Bendahara Umum',
                    'gambar_tandatangan'    => null,
                    'created_at'            => now(),
                    'updated_at'            => now(),
                ],
            ]);

            $this->command->info('Penandatangan berhasil dibuat');
        } else {
            $this->command->info('Penandatangan sudah ada');
        }
    }
}

