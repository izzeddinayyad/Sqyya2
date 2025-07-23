<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            InstitutionSeeder::class,
        ]);

        \App\Models\User::firstOrCreate([
            'email' => 'admin@admin.com',
        ], [
            'name' => 'Admin',
            'password' => bcrypt('admin@admin.com'),
            'role' => 'org_owner',
            'phone' => '0500000000',
            'address' => 'الرياض',
            'city' => 'الرياض',
        ]);

        \App\Models\User::create([
            'name' => 'سائق تجريبي',
            'email' => 'driver' . uniqid() . '@example.com',
            'password' => bcrypt('driver@123'),
            'role' => 'driver',
            'phone' => '0555555555',
            'address' => 'جدة',
            'city' => 'جدة',
        ]);
    }
}
