<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jamaah', function (Blueprint $table) {
            // ID ini akan menjadi LABEL (int) untuk algoritma LBPH
            $table->id(); 
            $table->string('nik', 16)->unique();
            $table->string('nama_lengkap');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('no_hp', 15)->nullable();
            $table->text('alamat')->nullable();
            
            // Status aktif berguna jika jamaah pindah/meninggal tanpa menghapus data historis
            $table->boolean('status_aktif')->default(true);
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jamaah');
    }
};