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
        Schema::create('pengajian_groups', function (Blueprint $table) {
            $table->id();
            $table->string('nama_group'); // Caberawit, Kelompok, Muda-mudi, Ibu-ibu/4S
            $table->string('deskripsi')->nullable();
            $table->boolean('has_rapot')->default(false); // Flag for Caberawit
            $table->timestamps();
        });

        Schema::create('pengajian_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajian_group_id')->constrained('pengajian_groups')->onDelete('cascade');
            $table->string('hari'); // Senin, Selasa... or numeric 1-7
            $table->time('jam_mulai'); // 18:30:00
            $table->time('jam_selesai')->nullable(); // 19:30:00
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajian_schedules');
        Schema::dropIfExists('pengajian_groups');
    }
};
