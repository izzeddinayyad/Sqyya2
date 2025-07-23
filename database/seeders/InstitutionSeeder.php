<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Station;
use App\Models\Truck;
use Illuminate\Support\Facades\Hash;

class InstitutionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Institution Owner 1
        $institution1 = User::create([
            'name' => 'أحمد محمد',
            'email' => 'ahmed@institution1.com',
            'password' => Hash::make('password'),
            'role' => 'org_owner',
            'phone' => '0501234567',
            'city' => 'الرياض',
            'institution_id' => null, // Institution owner has no institution_id
        ]);

        // Create Institution Owner 2
        $institution2 = User::create([
            'name' => 'فاطمة علي',
            'email' => 'fatima@institution2.com',
            'password' => Hash::make('password'),
            'role' => 'org_owner',
            'phone' => '0507654321',
            'city' => 'جدة',
            'institution_id' => null, // Institution owner has no institution_id
        ]);

        // Create drivers for Institution 1
        $driver1_1 = User::create([
            'name' => 'محمد أحمد',
            'email' => 'mohamed1@institution1.com',
            'password' => Hash::make('password'),
            'role' => 'driver',
            'phone' => '0501111111',
            'city' => 'الرياض',
            'institution_id' => $institution1->id,
        ]);

        $driver1_2 = User::create([
            'name' => 'علي محمد',
            'email' => 'ali1@institution1.com',
            'password' => Hash::make('password'),
            'role' => 'driver',
            'phone' => '0502222222',
            'city' => 'الرياض',
            'institution_id' => $institution1->id,
        ]);

        // Create drivers for Institution 2
        $driver2_1 = User::create([
            'name' => 'خالد فاطمة',
            'email' => 'khalid2@institution2.com',
            'password' => Hash::make('password'),
            'role' => 'driver',
            'phone' => '0503333333',
            'city' => 'جدة',
            'institution_id' => $institution2->id,
        ]);

        $driver2_2 = User::create([
            'name' => 'سارة فاطمة',
            'email' => 'sara2@institution2.com',
            'password' => Hash::make('password'),
            'role' => 'driver',
            'phone' => '0504444444',
            'city' => 'جدة',
            'institution_id' => $institution2->id,
        ]);

        // Create delegates for Institution 1
        $delegate1_1 = User::create([
            'name' => 'أحمد الشريف',
            'email' => 'ahmed.delegate1@institution1.com',
            'password' => Hash::make('password'),
            'role' => 'representative',
            'phone' => '0591234567',
            'city' => 'الرياض',
            'institution_id' => $institution1->id,
            'status' => 'active',
        ]);

        $delegate1_2 = User::create([
            'name' => 'سارة العلي',
            'email' => 'sara.delegate1@institution1.com',
            'password' => Hash::make('password'),
            'role' => 'representative',
            'phone' => '0569876543',
            'city' => 'الرياض',
            'institution_id' => $institution1->id,
            'status' => 'inactive',
        ]);

        // Create delegates for Institution 2
        $delegate2_1 = User::create([
            'name' => 'محمود حسن',
            'email' => 'mahmoud.delegate2@institution2.com',
            'password' => Hash::make('password'),
            'role' => 'representative',
            'phone' => '0597654321',
            'city' => 'جدة',
            'institution_id' => $institution2->id,
            'status' => 'active',
        ]);

        // Create stations for Institution 1
        Station::create([
            'name' => 'محطة التحلية الأولى',
            'location' => 'شارع الملك فهد، الرياض',
            'daily_capacity' => 1000,
            'status' => 'active',
            'city' => 'الرياض',
            'coordinates' => '24.7136,46.6753',
            'institution_id' => $institution1->id,
            'user_id' => $institution1->id,
        ]);

        Station::create([
            'name' => 'محطة التحلية الثانية',
            'location' => 'شارع العليا، الرياض',
            'daily_capacity' => 800,
            'status' => 'active',
            'city' => 'الرياض',
            'coordinates' => '24.7136,46.6753',
            'institution_id' => $institution1->id,
            'user_id' => $institution1->id,
        ]);

        // Create stations for Institution 2
        Station::create([
            'name' => 'محطة جدة المركزية',
            'location' => 'شارع التحلية، جدة',
            'daily_capacity' => 1200,
            'status' => 'active',
            'city' => 'جدة',
            'coordinates' => '21.4858,39.1925',
            'institution_id' => $institution2->id,
            'user_id' => $institution2->id,
        ]);

        // Create trucks for Institution 1
        Truck::create([
            'truck_number' => 'TRK-001',
            'truck_type' => 'شاحنة نقل مياه',
            'tank_capacity' => 5000,
            'status' => 'active',
            'driver_id' => $driver1_1->id,
            'maintenance_date' => '2025-07-15',
            'institution_id' => $institution1->id,
        ]);

        Truck::create([
            'truck_number' => 'TRK-002',
            'truck_type' => 'شاحنة نقل مياه',
            'tank_capacity' => 3000,
            'status' => 'active',
            'driver_id' => $driver1_2->id,
            'maintenance_date' => '2025-07-20',
            'institution_id' => $institution1->id,
        ]);

        // Create trucks for Institution 2
        Truck::create([
            'truck_number' => 'JED-001',
            'truck_type' => 'شاحنة نقل مياه',
            'tank_capacity' => 4000,
            'status' => 'active',
            'driver_id' => $driver2_1->id,
            'maintenance_date' => '2025-07-25',
            'institution_id' => $institution2->id,
        ]);

        Truck::create([
            'truck_number' => 'JED-002',
            'truck_type' => 'شاحنة نقل مياه',
            'tank_capacity' => 6000,
            'status' => 'maintenance',
            'driver_id' => null,
            'maintenance_date' => '2025-06-30',
            'institution_id' => $institution2->id,
        ]);

        // Create available drivers (not assigned to any institution)
        User::create([
            'name' => 'عبدالله المتاح',
            'email' => 'abdullah.available@example.com',
            'password' => Hash::make('password'),
            'role' => 'driver',
            'phone' => '0505555555',
            'city' => 'الرياض',
            'institution_id' => null, // Available for assignment
        ]);

        User::create([
            'name' => 'نور المتاحة',
            'email' => 'noor.available@example.com',
            'password' => Hash::make('password'),
            'role' => 'driver',
            'phone' => '0506666666',
            'city' => 'جدة',
            'institution_id' => null, // Available for assignment
        ]);

        User::create([
            'name' => 'يوسف المتاح',
            'email' => 'yousef.available@example.com',
            'password' => Hash::make('password'),
            'role' => 'driver',
            'phone' => '0507777777',
            'city' => 'الدمام',
            'institution_id' => null, // Available for assignment
        ]);

        // Create some available trucks (not assigned to drivers)
        Truck::create([
            'truck_number' => 'AVL-001',
            'truck_type' => 'شاحنة نقل مياه',
            'tank_capacity' => 3500,
            'status' => 'active',
            'driver_id' => null, // Available for assignment
            'maintenance_date' => '2025-08-15',
            'institution_id' => $institution1->id,
        ]);

        Truck::create([
            'truck_number' => 'AVL-002',
            'truck_type' => 'شاحنة نقل مياه',
            'tank_capacity' => 4500,
            'status' => 'active',
            'driver_id' => null, // Available for assignment
            'maintenance_date' => '2025-08-20',
            'institution_id' => $institution2->id,
        ]);
    }
} 