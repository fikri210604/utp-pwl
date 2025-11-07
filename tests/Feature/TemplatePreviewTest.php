<?php

namespace Tests\Feature;

use App\Models\PerihalSurat;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TemplatePreviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_preview_endpoint_renders_template(): void
    {
        // Login dulu (tanpa matikan middleware auth/security lain)
        $this->actingAs(User::factory()->create());

        // Insert data perihal & template
        $p = PerihalSurat::create([
            'nama_perihal'=> 'Undangan Rapat',
            'jenis_surat'=> 'undangan',
            'template_view'=> 'templates.undangan',
        ]);

        // Panggil endpoint preview
        $resp = $this->get(route('outgoing-letters.template-preview', [
            'perihal_surat_id' => $p->id
        ]));

        // Pastikan halaman berhasil dimuat
        $resp->assertStatus(200);

        // Pastikan beberapa teks kunci dari template muncul
        $resp->assertSee('Dengan hormat', false);
        $resp->assertSee('Kepada Yth', false);
    }
}
