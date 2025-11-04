<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NomorSurat;

class KodePihakSeeder extends Seeder
{
    public function run(): void
    {
        if (NomorSurat::count() == 0) {
            NomorSurat::insert([
                ['kode_pihak' => 'KETUM', 'nama_pihak' => 'Ketua Umum'],
                ['kode_pihak' => 'WAKETUM', 'nama_pihak' => 'Wakil Ketua Umum'],
                ['kode_pihak' => 'SEKUM', 'nama_pihak' => 'Sekretaris Umum'],
                ['kode_pihak' => 'BENDUM', 'nama_pihak' => 'Bendahara Umum'],
                ['kode_pihak' => 'KDS', 'nama_pihak' => 'Bidang Kaderisasi'],
                ['kode_pihak' => 'KAUM', 'nama_pihak' => 'Bidang Kajian dan Keumatan'],
                ['kode_pihak' => 'HUMAS', 'nama_pihak' => 'Bidang Hubungan Masyarakat'],
                ['kode_pihak' => 'MCR', 'nama_pihak' => 'Bidang Media Center Rois'],
                ['kode_pihak' => 'AKPRES', 'nama_pihak' => 'Bidang Akademik dan Prestasi'],
                ['kode_pihak' => 'KESMA', 'nama_pihak' => 'Biro Kesekretariatan dan Mushola'],
                ['kode_pihak' => 'DANUS', 'nama_pihak' => 'Biro Dana dan Usaha'],
                ['kode_pihak' => 'KEMUS', 'nama_pihak' => 'Biro Kemuslimahan'],
                ['kode_pihak' => 'PEMBINAAN', 'nama_pihak' => 'Biro Pembinaan'],
            ]);

            $this->command->info('Kode Pihak berhasil dibuat');
        }else{
            $this->command->info('Kode Pihak sudah ada');
        }
    }
}
