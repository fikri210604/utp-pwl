<?php

namespace Tests\Feature;

use App\Models\NomorSurat;
use App\Models\Penandatangan;
use App\Models\PerihalSurat;
use App\Models\SuratKeluar;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SuratKeluarTest extends TestCase
{
    use RefreshDatabase;

    protected function seedMasters(): array
    {
        // Minimal master data
        $user = User::create([
            'name' => 'Tester',
            'email' => 'tester@example.com',
            'password' => Hash::make('secret'),
            'role' => 'presidium',
            'jabatan' => 'Admin',
        ]);

        $kode = NomorSurat::create([
            'kode_pihak' => 'HUMAS',
            'nama_pihak' => 'Humas',
            'is_aktif' => true,
        ]);

        $perihal = PerihalSurat::create([
            'nama_perihal'  => 'Undangan Kegiatan',
            'jenis_surat'   => 'undangan',
            'template_view' => 'templates.undangan',
        ]);

        $ttd1 = Penandatangan::create([
            'nama_penandatangan' => 'Ketua Umum',
            'jabatan_penandatangan' => 'Ketua',
        ]);
        $ttd2 = Penandatangan::create([
            'nama_penandatangan' => 'Sekretaris Umum',
            'jabatan_penandatangan' => 'Sekretaris',
        ]);

        return compact('user','kode','perihal','ttd1','ttd2');
    }

    public function test_store_outgoing_letter_and_increment_number(): void
    {
        $this->withoutMiddleware();
        $master = $this->seedMasters();
        $this->actingAs($master['user']);

        // Create first letter
        $resp1 = $this->post(route('outgoing-letters.store'), [
            'tanggal_surat'    => now()->toDateString(),
            'tujuan'           => 'Tujuan A',
            'nomor_surat_id'   => $master['kode']->id,
            'perihal_surat_id' => $master['perihal']->perihal_surat_id,
            'penandatangan_ids'=> [$master['ttd1']->penandatangan_id, $master['ttd2']->penandatangan_id],
        ]);
        $resp1->assertStatus(302);

        $first = SuratKeluar::first();
        $this->assertNotNull($first);
        $this->assertStringStartsWith('001/', $first->nomor_surat);
        $this->assertCount(2, $first->penandatangans);

        // Create second letter same year should be 002/
        $resp2 = $this->post(route('outgoing-letters.store'), [
            'tanggal_surat'    => now()->toDateString(),
            'tujuan'           => 'Tujuan B',
            'nomor_surat_id'   => $master['kode']->id,
            'perihal_surat_id' => $master['perihal']->perihal_surat_id,
            'penandatangan_ids'=> [$master['ttd2']->penandatangan_id],
        ]);
        $resp2->assertStatus(302);

        $second = SuratKeluar::orderBy('created_at','desc')->first();
        $this->assertStringStartsWith('002/', $second->nomor_surat);
    }

    public function test_generate_pdf_saves_file_and_updates_status(): void
    {
        $this->withoutMiddleware();
        $master = $this->seedMasters();
        $this->actingAs($master['user']);
        Storage::fake('public');

        // Mock DomPDF facade
        $mock = new class {
            public function setPaper($size, $orientation) { return $this; }
            public function save($fullPath) { file_put_contents($fullPath, 'PDF-DUMMY'); return $this; }
        };
        Pdf::shouldReceive('loadView')->andReturn($mock);

        // Buat satu surat
        $resp = $this->post(route('outgoing-letters.store'), [
            'tanggal_surat' => now()->toDateString(),
            'tujuan' => 'Tujuan Cetak',
            'nomor_surat_id' => $master['kode']->id,
            'perihal_surat_id' => $master['perihal']->perihal_surat_id,
            'penandatangan_ids'=> [$master['ttd1']->penandatangan_id],
        ]);
        $resp->assertStatus(302);
        $letter = SuratKeluar::first();

        // Cetak PDF
        $print = $this->get(route('outgoing-letters.pdf', $letter));
        $print->assertStatus(200);

        $letter->refresh();
        $this->assertEquals('dicetak', $letter->status_surat);
        $this->assertNotNull($letter->file_pdf);
        Storage::disk('public')->assertExists($letter->file_pdf);
    }
}

