<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('water_requests', function (Blueprint $table) {
            $table->timestamp('assigned_at')->nullable()->after('driver_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('water_requests', function (Blueprint $table) {
            $table->dropColumn('assigned_at');
        });
    }
}; 