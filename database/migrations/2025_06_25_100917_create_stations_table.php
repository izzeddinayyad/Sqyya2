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
        Schema::create('stations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('location');
            $table->integer('daily_capacity')->nullable();
            $table->string('status')->default('active');
            $table->string('drivers')->nullable();
            $table->foreignId('institution_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('user_id')->constrained('users'); // دور المحطة
            $table->string('coordinates');
            $table->string('image')->nullable();
            $table->string('city');
            $table->integer('utilization')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stations');
    }
};
