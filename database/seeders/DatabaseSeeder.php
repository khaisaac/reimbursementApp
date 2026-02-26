<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('password123');

        // ── Users ────────────────────────────────────────────────
        $users = [
            [
                'name' => 'Rezaly',
                'email' => 'rezaly@hds.co.id',
                'position' => 'Admin',
                'role' => 'admin',
                'bank_name' => 'BCA',
                'account_number' => '1234567890',
                'status' => 'active',
            ],
            [
                'name' => 'Iwan',
                'email' => 'iwan@hds.co.id',
                'position' => 'PIC Project',
                'role' => 'pic_project',
                'bank_name' => 'Mandiri',
                'account_number' => '0987654321',
                'status' => 'active',
            ],
            [
                'name' => 'Pramudita Johan',
                'email' => 'pramudita@hds.co.id',
                'position' => 'Staff',
                'role' => 'user',
                'bank_name' => 'BCA',
                'account_number' => '1122334455',
                'status' => 'active',
            ],
            [
                'name' => 'Christian Yonathan',
                'email' => 'christian@hds.co.id',
                'position' => 'Staff',
                'role' => 'user',
                'bank_name' => 'BNI',
                'account_number' => '2233445566',
                'status' => 'active',
            ],
            [
                'name' => 'Roy Rikki',
                'email' => 'roy@hds.co.id',
                'position' => 'Staff',
                'role' => 'user',
                'bank_name' => 'Mandiri',
                'account_number' => '3344556677',
                'status' => 'active',
            ],
            [
                'name' => 'Sari Finance',
                'email' => 'sari@hds.co.id',
                'position' => 'Finance Manager',
                'role' => 'finance',
                'bank_name' => 'BCA',
                'account_number' => '4455667788',
                'status' => 'active',
            ],
            [
                'name' => 'Dedi Kurniawan',
                'email' => 'dedi@hds.co.id',
                'position' => 'Technician',
                'role' => 'user',
                'bank_name' => 'BRI',
                'account_number' => '5566778899',
                'status' => 'active',
            ],
            [
                'name' => 'Andi Pratama',
                'email' => 'andi@hds.co.id',
                'position' => 'Engineer',
                'role' => 'user',
                'bank_name' => 'BCA',
                'account_number' => '6677889900',
                'status' => 'active',
            ],
        ];

        foreach ($users as $userData) {
            User::create(array_merge($userData, [
                'password' => $password,
                'email_verified_at' => now(),
            ]));
        }

        // ── Projects ─────────────────────────────────────────────
        $projects = [
            ['project_no' => 'A6666001', 'project_name' => 'Marketing', 'pic_name' => 'Iwan', 'alt_pic_name' => 'Rezaly'],
            ['project_no' => 'A6666002', 'project_name' => 'Engineering', 'pic_name' => 'Iwan', 'alt_pic_name' => 'Rezaly'],
            ['project_no' => 'A6666003', 'project_name' => 'Operational', 'pic_name' => 'Iwan', 'alt_pic_name' => null],
            ['project_no' => 'A6666004', 'project_name' => 'Maintenance', 'pic_name' => 'Iwan', 'alt_pic_name' => null],
            ['project_no' => 'A6666005', 'project_name' => 'Construction', 'pic_name' => 'Iwan', 'alt_pic_name' => 'Rezaly'],
        ];

        foreach ($projects as $projectData) {
            Project::create($projectData);
        }

        // ── Sample Attendance Records (for allowance validation) ─
        $project1 = Project::where('project_no', 'A6666001')->first();
        $pramudita = User::where('email', 'pramudita@hds.co.id')->first();

        if ($project1 && $pramudita) {
            foreach (range(1, 10) as $day) {
                Attendance::create([
                    'date' => now()->startOfMonth()->addDays($day - 1),
                    'user_id' => $pramudita->id,
                    'project_id' => $project1->id,
                    'location_link' => 'https://maps.google.com/?q=-6.2088,106.8456',
                ]);
            }
        }
    }
}
