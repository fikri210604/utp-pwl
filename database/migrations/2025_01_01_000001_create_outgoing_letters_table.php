<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('surat_keluars', function (Blueprint $table) {

            // UUID sebagai primary key
            $table->uuid('id')->primary();

            $table->string('nomor_surat')->unique();
            $table->date('tanggal_surat');

            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            // Kode pihak (KETUM, SEKUM, PAN-Acara, dll)
            $table->foreignId('kode_pihak_id')
                ->nullable()
                ->constrained('nomor_surat')
                ->nullOnDelete();

            $table->string('tujuan');

            // Menentukan jenis & template surat
            $table->foreignId('perihal_surat_id')
                ->constrained('perihal_surats')
                ->cascadeOnDelete();

            // Field khusus untuk acara (dinamis tergantung jenis surat)
            $table->string('nama_kegiatan')->nullable();
            $table->string('lokasi_acara')->nullable();
            $table->string('hari_tanggal')->nullable();
            $table->string('waktu_acara')->nullable();

            // Catatan / paragraf tambahan bebas
            $table->longText('isi_tambahan')->nullable();

            // Penandatangan dinamis
            $table->foreignId('penandatangan_id')
                ->constrained('penandatangans')
                ->cascadeOnDelete();

            // File PDF hasil generate
            $table->string('file_pdf')->nullable();

            $table->enum('status_surat', ['draft', 'dicetak', 'dikirim', 'selesai'])
                ->default('draft');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_keluars');
    }
};
