<?php

namespace Database\Seeders;

use App\Models\College;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CollegeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Creates sample colleges with college admin users (as super admin would).
     */
    public function run(): void
    {
        $colleges = [
            [
                'name' => 'Sample College of Engineering',
                'code' => 'SCE',
                'contact_email' => 'admin@samplecollege.edu',
                'status' => 'active',
                'admin_name' => 'College Admin',
                'admin_email' => 'collegeadmin@samplecollege.edu',
                'admin_password' => 'password',
            ],
            [
                'name' => 'Demo Institute of Technology',
                'code' => 'DIT',
                'contact_email' => 'contact@demoinstitute.edu',
                'status' => 'active',
                'admin_name' => 'Demo College Admin',
                'admin_email' => 'admin@demoinstitute.edu',
                'admin_password' => 'password',
            ],
        ];

        foreach ($colleges as $data) {
            DB::transaction(function () use ($data) {
                $college = College::firstOrCreate(
                    ['code' => $data['code']],
                    [
                        'name' => $data['name'],
                        'contact_email' => $data['contact_email'],
                        'status' => $data['status'],
                    ]
                );

                User::firstOrCreate(
                    ['email' => $data['admin_email']],
                    [
                        'name' => $data['admin_name'],
                        'password' => Hash::make($data['admin_password']),
                        'role' => 'COLLEGE_ADMIN',
                        'college_id' => $college->id,
                    ]
                );
            });

            $this->command->info("College: {$data['name']} (Code: {$data['code']})");
            $this->command->info("  Admin Email: {$data['admin_email']}");
            $this->command->info("  Admin Password: {$data['admin_password']}");
        }

        $this->command->info('Colleges and College Admins seeded successfully.');
    }
}
