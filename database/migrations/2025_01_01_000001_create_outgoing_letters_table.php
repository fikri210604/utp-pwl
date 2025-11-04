<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('surat_keluars', function (Blueprint $table) {
            $table->id();

            $table->string('nomor_surat')->unique();

            $table->date('tanggal_surat');

            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');


            $table->foreignId('nomor_surat_id')
                ->nullable()
                ->constrained('nomor_surat')
                ->onDelete('set null');

            $table->string('tujuan');
            $table->string('perihal');
            $table->longText('isi_surat');

            $table->string('penandatangan')->nullable(); 
            $table->string('tanda_tangan')->nullable(); 
            $table->enum('status_surat', ['draft', 'dicetak', 'dikirim', 'selesai'])->default('draft');

            $table->string('file_pdf')->nullable(); 

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_keluars');
    }
};
