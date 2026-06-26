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
        Schema::table('attendances', function (Blueprint $table) {
            $table->enum('status_kehadiran', ['Hadir', 'Izin', 'Alpha', 'Sakit'])->default('Hadir')->after('waktu_hadir');
            $table->foreignId('pengajian_group_id')
                ->nullable()
                ->after('jamaah_id')
                ->constrained('pengajian_groups');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropForeign(['pengajian_group_id']);
            $table->dropColumn('pengajian_group_id');
            $table->dropColumn('status_kehadiran');
        });
    }
};
