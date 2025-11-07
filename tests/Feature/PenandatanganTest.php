<?php

namespace Tests\Feature;

use App\Models\Penandatangan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class PenandatanganTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_loads(): void
    {
        $this->actingAs(User::factory()->create());

        $resp = $this->get(route('penandatangan.index'));
        $resp->assertStatus(200);
    }

    public function test_create_update_delete_penandatangan(): void
    {
        $this->actingAs(User::factory()->create());
        Storage::fake('public');

        // CREATE
        $resp = $this->post(route('penandatangan.store'), [
            'nama_penandatangan' => 'Ketua Umum',
            'nip_npm_penandatangan' => '12345678',
            'jabatan_penandatangan'=> 'Ketua',
            'gambar_tandatangan' => UploadedFile::fake()->image('ttd.png', 100, 50),
        ]);
        $resp->assertRedirect();

        $p = Penandatangan::first();
        $this->assertNotNull($p);
        Storage::disk('public')->assertExists($p->gambar_tandatangan);

        // UPDATE (tanpa upload ulang file)
        $this->put(route('penandatangan.update', $p), [
            'nama_penandatangan'    => 'Ketua Umum Revisi',
            'nip_npm_penandatangan' => '87654321',
            'jabatan_penandatangan' => 'Ketua',
        ])->assertRedirect();

        $p->refresh();
        $this->assertEquals('Ketua Umum Revisi', $p->nama_penandatangan);

        // DELETE (soft delete)
        $this->delete(route('penandatangan.destroy', $p))->assertRedirect();
        $this->assertSoftDeleted('penandatangans', ['id' => $p->id]);
    }
}
