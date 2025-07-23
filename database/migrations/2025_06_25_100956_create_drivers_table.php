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
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->foreignId('institution_id')->constrained('users'); // دور المؤسسة
            $table->string('name');
            $table->string('phone');
            $table->string('license_number');
            $table->string('image')->nullable();
            $table->string('truck_number');
            $table->string('truck_type');
            $table->integer('tank_capacity');
            $table->date('maintenance_date')->nullable();
            $table->string('status')->default('active');
            $table->string('vehicle_info')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drivers');
    }
};
