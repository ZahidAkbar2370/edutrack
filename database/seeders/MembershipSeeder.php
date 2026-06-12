<?php

namespace Database\Seeders;

use App\Models\Membership;
use Illuminate\Database\Seeder;

class MembershipSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'membership_name' => 'Basic',
                'membership_price' => '1000',
                'students_limit' => 50,
                'teachers_limit' => 10,
                'allowed_attendance' => true,
                'allowed_daily_test' => true,
                'allowed_fee_management' => true,
                'allowed_student_card' => true,
                'allowed_whatsapp_message' => true,
                'allowed_whatsapp_announcement' => true,
            ],
            [
                'membership_name' => 'Standard',
                'membership_price' => '1800',
                'students_limit' => 100,
                'teachers_limit' => 25,
                'allowed_attendance' => true,
                'allowed_daily_test' => true,
                'allowed_fee_management' => true,
                'allowed_student_card' => true,
                'allowed_whatsapp_message' => true,
                'allowed_whatsapp_announcement' => true,
            ],
            [
                'membership_name' => 'Premium',
                'membership_price' => '3500',
                'students_limit' => null, // unlimited
                'teachers_limit' => 50,
                'allowed_attendance' => true,
                'allowed_daily_test' => true,
                'allowed_fee_management' => true,
                'allowed_student_card' => true,
                'allowed_whatsapp_message' => true,
                'allowed_whatsapp_announcement' => true,
            ],
            [
                'membership_name' => 'Diamond',
                'membership_price' => '6000',
                'students_limit' => null, // unlimited
                'teachers_limit' => 100, // unlimited
                'allowed_attendance' => true,
                'allowed_daily_test' => true,
                'allowed_fee_management' => true,
                'allowed_student_card' => true,
                'allowed_whatsapp_message' => true,
                'allowed_whatsapp_announcement' => true,
            ],
        ];

        foreach ($plans as $plan) {
            Membership::updateOrCreate(
                $plan
            );
        }
    }
}
