<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\WaterRequest;
use App\Models\User;

class WaterRequestsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get a representative user
        $representative = User::where('role', 'representative')->first();
        
        if (!$representative) {
            // Create a representative if none exists
            $representative = User::create([
                'name' => 'أحمد المندوب',
                'email' => 'delegate@example.com',
                'password' => bcrypt('password'),
                'role' => 'representative',
                'institution_id' => 1,
            ]);
        }

        // Create sample water requests
        $requests = [
            [
                'user_id' => $representative->id,
                'representative_id' => $representative->id,
                'emergency' => false,
                'quantity' => 3000,
                'status' => 'pending',
                'location' => 'شارع الملك فهد، حي النزهة، مبنى رقم 15، شقة 302',
                'scheduled_at' => now()->addDays(1)->setTime(10, 0),
                'notes' => 'يرجى الاتصال قبل الوصول، البوابة الأمامية مقفلة',
            ],
            [
                'user_id' => $representative->id,
                'representative_id' => $representative->id,
                'emergency' => true,
                'quantity' => 5000,
                'status' => 'approved',
                'location' => 'طريق الأمير محمد، حي الورود، فيلا رقم 8',
                'scheduled_at' => now()->addHours(2),
                'notes' => 'طلب عاجل - نفاد المياه تماماً',
            ],
            [
                'user_id' => $representative->id,
                'representative_id' => $representative->id,
                'emergency' => false,
                'quantity' => 2000,
                'status' => 'completed',
                'location' => 'شارع التحلية، حي الشاطئ، برج الأفق، الطابق 12',
                'scheduled_at' => now()->subDays(1)->setTime(14, 30),
                'notes' => 'التوصيل في المساء بعد الساعة 6',
            ],
            [
                'user_id' => $representative->id,
                'representative_id' => $representative->id,
                'emergency' => false,
                'quantity' => 4000,
                'status' => 'rejected',
                'location' => 'طريق الملك عبدالله، حي الملقا، مجمع سكني رقم 3',
                'scheduled_at' => now()->addDays(2)->setTime(9, 0),
                'notes' => 'المنطقة خارج نطاق التغطية',
            ],
        ];

        foreach ($requests as $requestData) {
            WaterRequest::create($requestData);
        }
    }
}
