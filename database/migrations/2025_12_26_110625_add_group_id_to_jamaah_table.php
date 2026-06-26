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
        Schema::table('jamaah', function (Blueprint $table) {
            $table->foreignId('pengajian_group_id')
                ->nullable()
                ->after('alamat')
                ->constrained('pengajian_groups')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jamaah', function (Blueprint $table) {
            $table->dropForeign(['pengajian_group_id']);
            $table->dropColumn('pengajian_group_id');
        });
    }
};
