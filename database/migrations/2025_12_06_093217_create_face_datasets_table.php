<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('face_datasets', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke tabel jamaah. Cascade: Hapus jamaah = Hapus dataset
            $table->foreignId('jamaah_id')
                  ->constrained('jamaah')
                  ->onDelete('cascade');
            
            // Path relatif penyimpanan foto (contoh: datasets/101/img01.jpg)
            $table->string('image_path');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('face_datasets');
    }
};