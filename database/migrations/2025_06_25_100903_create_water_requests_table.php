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
        Schema::create('water_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('point_id')->nullable()->constrained('distribution_points');
            $table->boolean('emergency')->default(false);
            $table->integer('quantity');
            $table->string('status')->default('pending');
            $table->timestamp('scheduled_time')->nullable();
            $table->string('current_location')->nullable(); // لتتبع الصهريج
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('water_requests');
    }
};
