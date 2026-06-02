<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Student;
use App\Models\SppType;
use App\Models\Invoice;
use App\Models\PaymentMethod;
use App\Models\DiscountRule;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Admin
        User::create([
            'name' => 'Admin Utama',
            'email' => 'admin@spp.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // 2. Create Parent (Sarah)
        $parent = User::create([
            'name' => 'Sarah',
            'email' => 'sarah@spp.com',
            'password' => Hash::make('password'),
            'role' => 'parent',
            'phone' => '081234567890',
        ]);

        // 3. Create Student
        $student = Student::create([
            'parent_id' => $parent->id,
            'name' => 'Budi',
            'nisn' => '1234567890',
            'class_name' => '10A',
            'anak_guru' => false,
        ]);

        // 4. Create SPP Types
        $sppType = SppType::create([
            'name' => 'SPP Reguler',
            'amount' => 1500000.00,
            'description' => 'SPP Bulanan',
        ]);

        // 5. Create Payment Methods
        PaymentMethod::insert([
            ['name' => 'BRIVA', 'code' => 'BRIVA', 'logo_url' => 'https://upload.wikimedia.org/wikipedia/commons/9/97/Logo_BRI.png'],
            ['name' => 'GoPay', 'code' => 'GOPAY', 'logo_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/8/86/Gopay_logo.svg/2560px-Gopay_logo.svg.png'],
            ['name' => 'BCA VA', 'code' => 'BCAVA', 'logo_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/5/5c/Bank_Central_Asia.svg/1024px-Bank_Central_Asia.svg.png'],
        ]);

        // 6. Create Discount Rules
        DiscountRule::create([
            'name' => 'Diskon Anak Guru',
            'condition' => 'Siswa.anak_guru == True',
            'discount_percentage' => 50.00,
            'is_active' => true,
        ]);

        // 7. Create Invoices for Sarah (1 Tunggakan, 1 Menunggu)
        // Tunggakan
        Invoice::create([
            'student_id' => $student->id,
            'spp_type_id' => $sppType->id,
            'month' => 'Mei',
            'year' => 2026,
            'amount' => 1500000.00,
            'discount' => 0,
            'total_amount' => 1500000.00,
            'status' => 'Tunggakan',
            'due_date' => Carbon::create(2026, 5, 10),
        ]);

        // Menunggu (Current)
        Invoice::create([
            'student_id' => $student->id,
            'spp_type_id' => $sppType->id,
            'month' => 'Juni',
            'year' => 2026,
            'amount' => 1500000.00,
            'discount' => 0,
            'total_amount' => 1500000.00,
            'status' => 'Menunggu',
            'due_date' => Carbon::create(2026, 6, 10),
        ]);
    }
}
