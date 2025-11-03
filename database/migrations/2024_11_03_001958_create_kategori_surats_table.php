<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('nomor_surat', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('kode_surat')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('jenis_surat');
            $table->year('tahun_surat');
            $table->string('format_nomor_surat');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kategori_surats');
    }
};
