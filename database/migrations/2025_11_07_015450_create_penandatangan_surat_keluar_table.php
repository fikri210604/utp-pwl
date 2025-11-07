<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surat_penandatangans', function (Blueprint $table) {
            $table->id();
            $table->uuid('surat_keluar_id');
            $table->foreign('surat_keluar_id')->references('id')->on('surat_keluars')->cascadeOnDelete();

            $table->foreignId('penandatangan_id')
                  ->references('penandatangan_id')
                  ->on('penandatangans')
                  ->cascadeOnDelete();

            // urutan tampil saat dicetak
            $table->unsignedInteger('urutan_ttd')->default(1);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_penandatangans');
    }
};
