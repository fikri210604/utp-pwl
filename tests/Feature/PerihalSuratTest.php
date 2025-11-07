<?php

namespace Tests\Feature;

use App\Models\PerihalSurat;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PerihalSuratTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_loads(): void
    {
        $this->actingAs(User::factory()->create());

        $resp = $this->get(route('perihal_surat.index'));
        $resp->assertStatus(200);
    }

    public function test_create_update_delete_perihal(): void
    {
        $this->actingAs(User::factory()->create());

        // Unit Test CREATE
        $this->post(route('perihal_surat.store'), [
            'nama_perihal'=> 'Undangan Kegiatan X',
            'jenis_surat'=> 'undangan',
            'template_view'=> 'templates.undangan',
        ])->assertRedirect();

        $perihal = PerihalSurat::first();
        $this->assertNotNull($perihal);
        $this->assertSame('templates.undangan', $perihal->template_view);

        // UPDATE
        $this->put(route('perihal_surat.update', $perihal), [
            'nama_perihal'=> 'Undangan Kegiatan Y',
            'jenis_surat'=> 'undangan',
            'template_view'=> 'templates.undangan',
        ])->assertRedirect();

        $perihal->refresh();
        $this->assertSame('Undangan Kegiatan Y', $perihal->nama_perihal);

        // DELETE (soft delete)
        $this->delete(route('perihal_surat.destroy', $perihal))->assertRedirect();

        $this->assertSoftDeleted('perihal_surats', ['id' => $perihal->id]);
    }
}
