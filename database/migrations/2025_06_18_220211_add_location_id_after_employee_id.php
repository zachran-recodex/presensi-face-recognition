<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add location_id column after employee_id using raw SQL
        DB::statement('ALTER TABLE users ADD COLUMN location_id BIGINT UNSIGNED NULL AFTER employee_id');
        DB::statement('ALTER TABLE users ADD CONSTRAINT users_location_id_foreign FOREIGN KEY (location_id) REFERENCES locations(id) ON DELETE SET NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['location_id']);
            $table->dropColumn('location_id');
        });
    }
};
