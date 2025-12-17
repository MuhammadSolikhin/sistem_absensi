<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('jamaah_id')
                  ->constrained('jamaah')
                  ->onDelete('cascade');
            
            $table->dateTime('waktu_hadir');
            $table->date('tanggal'); // Kolom terpisah untuk memudahkan filter laporan
            
            // Path foto capture saat kejadian (bukti visual)
            $table->string('capture_image_path')->nullable();
            
            // Simpan nilai confidence score untuk analisa akurasi skripsi
            // Float karena biasanya nilainya desimal (e.g., 45.23)
            $table->float('confidence_score')->nullable();
            
            $table->string('lokasi_kamera')->default('Utama');
            
            $table->timestamps();
            
            // INDEXING: Sangat penting untuk performa query laporan per tanggal
            $table->index(['jamaah_id', 'tanggal']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};