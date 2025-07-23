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
            $table->foreignId('truck_id')->nullable()->after('status')->constrained('trucks')->onDelete('set null');
            $table->foreignId('driver_id')->nullable()->after('truck_id')->constrained('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('water_requests', function (Blueprint $table) {
            $table->dropForeign(['truck_id']);
            $table->dropForeign(['driver_id']);
            $table->dropColumn(['truck_id', 'driver_id']);
        });
    }
}; 