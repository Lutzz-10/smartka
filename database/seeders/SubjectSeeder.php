<?php
namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        $subjects = [
            // Kelas 6 SD
            ['name' => 'Matematika',       'class_level' => '6',  'color_hex' => '#1a56db', 'icon' => 'calculator'],
            ['name' => 'IPA',              'class_level' => '6',  'color_hex' => '#0e9f6e', 'icon' => 'flask'],
            ['name' => 'Bahasa Indonesia', 'class_level' => '6',  'color_hex' => '#f59e0b', 'icon' => 'book-open'],
            ['name' => 'IPS',              'class_level' => '6',  'color_hex' => '#8b5cf6', 'icon' => 'globe'],

            // Kelas 9 SMP
            ['name' => 'Matematika',       'class_level' => '9',  'color_hex' => '#1a56db', 'icon' => 'calculator'],
            ['name' => 'IPA',              'class_level' => '9',  'color_hex' => '#0e9f6e', 'icon' => 'flask'],
            ['name' => 'IPS',              'class_level' => '9',  'color_hex' => '#8b5cf6', 'icon' => 'globe'],
            ['name' => 'Bahasa Indonesia', 'class_level' => '9',  'color_hex' => '#f59e0b', 'icon' => 'book-open'],
            ['name' => 'Bahasa Inggris',   'class_level' => '9',  'color_hex' => '#ef4444', 'icon' => 'languages'],

            // Kelas 12 SMA
            ['name' => 'TPS',              'class_level' => '12', 'color_hex' => '#1a56db', 'icon' => 'brain'],
            ['name' => 'Literasi',         'class_level' => '12', 'color_hex' => '#f59e0b', 'icon' => 'book-open'],
            ['name' => 'Matematika',       'class_level' => '12', 'color_hex' => '#0e9f6e', 'icon' => 'calculator'],
            ['name' => 'IPA Terpadu',      'class_level' => '12', 'color_hex' => '#8b5cf6', 'icon' => 'flask'],
            ['name' => 'IPS Terpadu',      'class_level' => '12', 'color_hex' => '#ef4444', 'icon' => 'globe'],
        ];

        foreach ($subjects as $subject) {
            Subject::create($subject);
        }
    }
}