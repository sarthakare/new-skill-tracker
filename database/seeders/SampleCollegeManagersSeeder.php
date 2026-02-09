<?php

namespace Database\Seeders;

use App\Models\College;
use App\Models\IndependentTrainer;
use App\Models\InternalManager;
use App\Models\Vendor;
use Illuminate\Database\Seeder;

class SampleCollegeManagersSeeder extends Seeder
{
    /**
     * Seed vendors, independent trainers, and internal managers for Sample College of Engineering (SCE).
     */
    public function run(): void
    {
        $college = College::where('code', 'SCE')->first();

        if (! $college) {
            $this->command->warn('Sample College of Engineering (SCE) not found. Run CollegeSeeder first.');

            return;
        }

        $collegeId = $college->id;

        // Vendors
        $vendors = [
            ['name' => 'Tech Skills Training Co', 'type' => 'Training', 'contact_email' => 'contact@techskillstraining.com', 'contact_phone' => '+1-555-100-1001', 'address' => '123 Training Ave'],
            ['name' => 'CertPro Solutions', 'type' => 'Certification', 'contact_email' => 'info@certpro.com', 'contact_phone' => '+1-555-100-1002', 'address' => '456 Cert Blvd'],
            ['name' => 'Campus Logistics Ltd', 'type' => 'Logistics', 'contact_email' => 'events@campuslogistics.com', 'contact_phone' => '+1-555-100-1003', 'address' => '789 Logistics Way'],
        ];

        foreach ($vendors as $data) {
            Vendor::firstOrCreate(
                ['college_id' => $collegeId, 'name' => $data['name']],
                [
                    'type' => $data['type'],
                    'contact_email' => $data['contact_email'],
                    'contact_phone' => $data['contact_phone'],
                    'address' => $data['address'],
                ]
            );
        }

        $this->command->info('Vendors: '.count($vendors).' created/verified for Sample College of Engineering.');

        // Independent Trainers
        $trainers = [
            ['name' => 'Dr. Jane Smith', 'email' => 'jane.smith@trainer.com', 'phone' => '+1-555-200-2001', 'expertise' => 'Software Development & Agile'],
            ['name' => 'Mike Johnson', 'email' => 'mike.j@trainer.com', 'phone' => '+1-555-200-2002', 'expertise' => 'Cloud & DevOps'],
            ['name' => 'Sarah Williams', 'email' => 'sarah.w@trainer.com', 'phone' => '+1-555-200-2003', 'expertise' => 'Data Science & ML'],
        ];

        foreach ($trainers as $data) {
            IndependentTrainer::firstOrCreate(
                ['college_id' => $collegeId, 'email' => $data['email']],
                [
                    'name' => $data['name'],
                    'phone' => $data['phone'],
                    'expertise' => $data['expertise'],
                ]
            );
        }

        $this->command->info('Independent Trainers: '.count($trainers).' created/verified for Sample College of Engineering.');

        // Internal Managers
        $managers = [
            ['name' => 'Alex Rivera', 'department' => 'Engineering', 'email' => 'alex.rivera@samplecollege.edu', 'phone' => '+1-555-300-3001'],
            ['name' => 'Pat Lee', 'department' => 'Skills Development', 'email' => 'pat.lee@samplecollege.edu', 'phone' => '+1-555-300-3002'],
            ['name' => 'Jordan Kim', 'department' => 'Student Affairs', 'email' => 'jordan.kim@samplecollege.edu', 'phone' => '+1-555-300-3003'],
        ];

        foreach ($managers as $data) {
            InternalManager::firstOrCreate(
                ['college_id' => $collegeId, 'email' => $data['email']],
                [
                    'name' => $data['name'],
                    'department' => $data['department'],
                    'phone' => $data['phone'],
                ]
            );
        }

        $this->command->info('Internal Managers: '.count($managers).' created/verified for Sample College of Engineering.');
        $this->command->info('Sample College (SCE) managers seeding completed.');
    }
}
