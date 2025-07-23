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
            // إضافة الحقول المطلوبة لنموذج طلب المياه
            $table->text('location')->nullable()->after('current_location'); // الموقع/العنوان
            $table->decimal('latitude', 10, 8)->nullable()->after('location'); // خط العرض
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude'); // خط الطول
            $table->timestamp('scheduled_at')->nullable()->after('scheduled_time'); // التاريخ والوقت المفضل
            $table->text('notes')->nullable()->after('scheduled_at'); // ملاحظات إضافية
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('water_requests', function (Blueprint $table) {
            $table->dropColumn(['location', 'latitude', 'longitude', 'scheduled_at', 'notes']);
        });
    }
};
