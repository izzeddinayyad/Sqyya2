<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class NotVerifiedLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_cannot_login_if_email_not_verified()
    {
        // أنشئ مستخدم بدون تفعيل البريد
        $user = User::factory()->create([
            'email' => 'notverified@example.com',
            'password' => bcrypt('password123'),
            'email_verified_at' => null,
        ]);

        $response = $this->post('/login', [
            'email' => 'notverified@example.com',
            'password' => 'password123',
        ]);

        // حسب منطقك، إذا كان هناك redirect أو رسالة خطأ معينة
        $response->assertRedirect('/login');
        $response->assertSessionHas('error'); // أو الرسالة التي تظهر عندك
    }

    public function test_user_can_login_if_email_verified()
    {
        // أنشئ مستخدم مفعل البريد
        $user = \App\Models\User::factory()->create([
            'email' => 'verified@example.com',
            'password' => bcrypt('password123'),
            'email_verified_at' => now(),
        ]);

        $response = $this->post('/login', [
            'email' => 'verified@example.com',
            'password' => 'password123',
        ]);

        // يجب أن يتم إعادة التوجيه للصفحة الرئيسية أو لوحة التحكم حسب منطقك
        $response->assertRedirect(); // يمكنك تخصيصها: ->assertRedirect('/home') أو أي صفحة أخرى
        $this->assertAuthenticatedAs($user);
    }

    public function test_unverified_user_cannot_create_water_request()
    {
        $user = \App\Models\User::factory()->create([
            'email_verified_at' => null,
            'password' => bcrypt('password123'),
        ]);

        $this->actingAs($user);

        $response = $this->post('/delegate/water-requests', [
            'quantity' => 10,
            'location' => 'test location',
        ]);

        // حسب منطقك: هل هناك redirect أو رسالة خطأ؟
        $response->assertRedirect('/somewhere'); // أو assertSessionHas('error')
    }
}
