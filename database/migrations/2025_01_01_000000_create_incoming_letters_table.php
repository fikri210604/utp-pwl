<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('surat_masuks', function (Blueprint $table) {
            $table->id();

            // Nomor surat dari pihak eksternal (manual input)
            $table->string('nomor_surat')->unique();
            $table->date('tanggal_surat');

            // Relasi ke user (yang menerima atau yang input data)
            $table->foreignId('penerima_id')
                  ->constrained('users')
                  ->onDelete('cascade');

            $table->string('pengirim'); // nama instansi/individu pengirim surat
            $table->string('perihal');
            $table->date('tanggal_diterima')->nullable();
            $table->text('keterangan')->nullable();
            $table->string('file_path')->nullable(); // path file PDF surat
            
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_masuks');
    }
};
