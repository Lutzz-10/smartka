<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
{
        $this->call([
            AdminSeeder::class,
            SubjectSeeder::class,
            SettingSeeder::class,
            LatihanSeeder::class,
            QuestionSeeder::class,
        ]);
}
}
