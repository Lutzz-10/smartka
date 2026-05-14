<?php
namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            [
                'key'         => 'ai_system_prompt',
                'value'       => 'Kamu adalah Smartka AI, asisten tutor belajar yang ramah dan cerdas untuk siswa Indonesia.',
                'description' => 'System prompt utama untuk Gemini AI',
            ],
            [
                'key'         => 'ai_daily_free_limit',
                'value'       => '5',
                'description' => 'Batas pertanyaan AI per hari untuk user free',
            ],
            [
                'key'         => 'maintenance_mode',
                'value'       => 'false',
                'description' => 'Mode maintenance website',
            ],
            [
                'key'         => 'app_version',
                'value'       => '1.0.0',
                'description' => 'Versi aplikasi SMARTKA',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::create($setting);
        }
    }
}