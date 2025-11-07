<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('penandatangans', function (Blueprint $table) {
            $table->id('penandatangan_id')->primary();
            $table->string('nama_penandatangan');
            $table->bigInteger('nip_npm_penandatangan');
            $table->string('jabatan_penandatangan');
            $table->string('gambar_tandatangan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penandatangans');
    }
};
