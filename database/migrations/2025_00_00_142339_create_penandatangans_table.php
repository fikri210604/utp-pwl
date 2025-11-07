<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penandatangans', function (Blueprint $table) {
            $table->id('penandatangan_id');
            $table->string('nama_penandatangan');
            $table->string('nip_npm_penandatangan', 30)->nullable(); // lebih aman
            $table->string('jabatan_penandatangan');
            $table->string('gambar_tandatangan')->nullable(); // upload opsional
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penandatangans');
    }
};
