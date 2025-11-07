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
        Schema::create('perihal_surats', function (Blueprint $table) {
            $table->id('perihal_surat_id');
            $table->string('nama_perihal', 100);
            // Jenis surat (undangan/peminjaman/lainnya) boleh berulang di beberapa perihal
            $table->string('jenis_surat', 50)->index();
            // Nama view blade untuk template preview/print, contoh: "templates.undangan"
            $table->string('template_view')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perihal_surats');
    }
};
