<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('surat_masuks', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_surat')->unique()->index();
            $table->date('tanggal_surat');
            
            $table->foreignId('nomor_surat_id')->constrained('nomor_surat')->onDelete('cascade');

            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            $table->string('pengirim');
            $table->string('penerima'); 
            $table->string('perihal');
            $table->date('tanggal_diterima')->nullable();
            $table->text('keterangan')->nullable();
            $table->text('file_path')->nullable(); 
            
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_masuks');
    }
};
