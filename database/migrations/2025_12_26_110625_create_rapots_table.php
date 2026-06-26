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
        Schema::create('rapots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jamaah_id')->constrained('jamaah')->onDelete('cascade');
            $table->string('periode'); // e.g. "Semester 1 2025", "Bulan Januari 2025"
            $table->text('catatan_wali')->nullable();
            $table->json('nilai')->nullable(); // Flexible: {"bacaan": "A", "hafalan": "B"}
            $table->enum('keputusan', ['Naik Kelas', 'Tinggal Kelas', 'Lulus', '-'])->default('-');
            $table->foreignId('created_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rapots');
    }
};
