<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modifying ENUM using raw SQL is often the most reliable way in MySQL
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'pengurus', 'guru') DEFAULT 'pengurus'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'pengurus') DEFAULT 'pengurus'");
    }
};
