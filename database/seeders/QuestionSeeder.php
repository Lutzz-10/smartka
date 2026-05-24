<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Subject;
use App\Models\Topic;
use App\Models\Question;
use App\Models\User;

class QuestionSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get or create admin user for created_by
        $adminUser = User::first();
        if (!$adminUser) {
            $adminUser = User::factory()->create(['email' => 'admin@smartka.test']);
        }

        // Seed Subjects
        $subjects = [
            ['id' => 1, 'name' => 'Matematika', 'description' => 'Mata pelajaran Matematika'],
            ['id' => 2, 'name' => 'Bahasa Indonesia', 'description' => 'Mata pelajaran Bahasa Indonesia'],
            ['id' => 3, 'name' => 'Bahasa Inggris', 'description' => 'Mata pelajaran Bahasa Inggris'],
            ['id' => 4, 'name' => 'IPA', 'description' => 'Mata pelajaran IPA (Sains)'],
            ['id' => 5, 'name' => 'IPS', 'description' => 'Mata pelajaran IPS'],
            ['id' => 6, 'name' => 'Biologi', 'description' => 'Mata pelajaran Biologi'],
            ['id' => 7, 'name' => 'Kimia', 'description' => 'Mata pelajaran Kimia'],
            ['id' => 8, 'name' => 'Fisika', 'description' => 'Mata pelajaran Fisika'],
            ['id' => 9, 'name' => 'Sejarah', 'description' => 'Mata pelajaran Sejarah'],
            ['id' => 10, 'name' => 'Geografi', 'description' => 'Mata pelajaran Geografi'],
            ['id' => 11, 'name' => 'Ekonomi', 'description' => 'Mata pelajaran Ekonomi'],
            ['id' => 12, 'name' => 'Sosiologi', 'description' => 'Mata pelajaran Sosiologi'],
            ['id' => 13, 'name' => 'PKN', 'description' => 'Mata pelajaran Pendidikan Kewarganegaraan'],
            ['id' => 14, 'name' => 'PJOK', 'description' => 'Mata pelajaran Pendidikan Jasmani, Olahraga, dan Kesehatan'],
        ];

        foreach ($subjects as $subject) {
            Subject::updateOrCreate(
                ['id' => $subject['id']],
                $subject
            );
        }

        // Seed Topics
        $topics = [
            ['id' => 1, 'subject_id' => 1, 'name' => 'Bilangan Bulat', 'description' => 'Pelajaran tentang bilangan bulat'],
            ['id' => 2, 'subject_id' => 1, 'name' => 'Pecahan', 'description' => 'Pelajaran tentang bilangan pecahan'],
            ['id' => 3, 'subject_id' => 6, 'name' => 'Sel', 'description' => 'Struktur dan fungsi sel'],
            ['id' => 4, 'subject_id' => 6, 'name' => 'Genetika', 'description' => 'Hukum pewarisan sifat'],
            ['id' => 5, 'subject_id' => 10, 'name' => 'Peta dan Globe', 'description' => 'Alat-alat geografi'],
            ['id' => 6, 'subject_id' => 8, 'name' => 'Gerak dan Gaya', 'description' => 'Hukum Newton'],
            ['id' => 7, 'subject_id' => 8, 'name' => 'Usaha dan Energi', 'description' => 'Konsep usaha dan energi'],
            ['id' => 8, 'subject_id' => 7, 'name' => 'Ikatan Kimia', 'description' => 'Jenis-jenis ikatan kimia'],
            ['id' => 9, 'subject_id' => 9, 'name' => 'Sejarah Kuno', 'description' => 'Sejarah periode kuno'],
            ['id' => 10, 'subject_id' => 2, 'name' => 'Puisi', 'description' => 'Analisis puisi Indonesia'],
            ['id' => 11, 'subject_id' => 3, 'name' => 'Present Tense', 'description' => 'Tense dalam Bahasa Inggris'],
            ['id' => 12, 'subject_id' => 4, 'name' => 'Ekosistem', 'description' => 'Struktur dan dinamika ekosistem'],
            ['id' => 13, 'subject_id' => 5, 'name' => 'Kemerdekaan', 'description' => 'Sejarah kemerdekaan Indonesia'],
            ['id' => 14, 'subject_id' => 11, 'name' => 'Permintaan dan Penawaran', 'description' => 'Hukum permintaan dan penawaran'],
            ['id' => 15, 'subject_id' => 12, 'name' => 'Mobilitas Sosial', 'description' => 'Konsep mobilitas sosial'],
        ];

        foreach ($topics as $topic) {
            Topic::updateOrCreate(
                ['id' => $topic['id']],
                $topic
            );
        }

        // Seed Questions
        $questions = [
            [
                'subject_id' => 1,
                'topic_id' => 1,
                'class_level' => '6',
                'difficulty' => 'easy',
                'type' => 'multiple_choice',
                'question_text' => 'Berapakah hasil dari 5 + (-3)?',
                'question_image' => null,
                'option_a' => '2',
                'option_b' => '-2',
                'option_c' => '8',
                'option_d' => '-8',
                'option_e' => null,
                'correct_answer' => 'a',
                'explanation_text' => 'Ketika kita menjumlahkan 5 dengan -3, kita mendapatkan 5 - 3 = 2.',
                'explanation_video_url' => null,
                'source' => null,
                'status' => 'active',
                'created_by' => $adminUser->id,
            ],
            [
                'subject_id' => 1,
                'topic_id' => 2,
                'class_level' => '6',
                'difficulty' => 'medium',
                'type' => 'short_answer',
                'question_text' => 'Tuliskan bentuk desimal dari pecahan 3/4!',
                'question_image' => null,
                'option_a' => null,
                'option_b' => null,
                'option_c' => null,
                'option_d' => null,
                'option_e' => null,
                'correct_answer' => '0.75',
                'explanation_text' => '3/4 = 3 ÷ 4 = 0.75',
                'explanation_video_url' => null,
                'source' => null,
                'status' => 'active',
                'created_by' => $adminUser->id,
            ],
            [
                'subject_id' => 6,
                'topic_id' => 3,
                'class_level' => '9',
                'difficulty' => 'medium',
                'type' => 'multiple_choice',
                'question_text' => 'Organel sel yang berfungsi sebagai pusat energi sel adalah...',
                'question_image' => null,
                'option_a' => 'Ribosom',
                'option_b' => 'Mitokondria',
                'option_c' => 'Lisosom',
                'option_d' => 'Retikulum Endoplasma',
                'option_e' => null,
                'correct_answer' => 'b',
                'explanation_text' => 'Mitokondria dikenal sebagai "pembangkit tenaga sel" karena memproduksi ATP melalui respirasi aerobik.',
                'explanation_video_url' => null,
                'source' => null,
                'status' => 'active',
                'created_by' => $adminUser->id,
            ],
            [
                'subject_id' => 6,
                'topic_id' => 4,
                'class_level' => '9',
                'difficulty' => 'hard',
                'type' => 'short_answer',
                'question_text' => 'Jelaskan apa itu alel dan berikan contohnya!',
                'question_image' => null,
                'option_a' => null,
                'option_b' => null,
                'option_c' => null,
                'option_d' => null,
                'option_e' => null,
                'correct_answer' => 'Alel adalah bentuk alternatif dari gen yang mengontrol sifat yang sama. Contoh: gen untuk warna mata memiliki alel cokelat dan alel biru.',
                'explanation_text' => 'Alel adalah variasi gen di lokus yang sama pada kromosom homolog. Setiap individu memiliki dua alel untuk setiap sifat (diploid).',
                'explanation_video_url' => null,
                'source' => null,
                'status' => 'active',
                'created_by' => $adminUser->id,
            ],
            [
                'subject_id' => 10,
                'topic_id' => 5,
                'class_level' => '12',
                'difficulty' => 'easy',
                'type' => 'multiple_choice',
                'question_text' => 'Skala pada peta berfungsi untuk...',
                'question_image' => null,
                'option_a' => 'Menunjukkan arah',
                'option_b' => 'Memperkecil ukuran wilayah nyata',
                'option_c' => 'Menentukan batas wilayah',
                'option_d' => 'Menambahkan warna peta',
                'option_e' => null,
                'correct_answer' => 'b',
                'explanation_text' => 'Skala adalah perbandingan antara ukuran pada peta dengan ukuran sebenarnya di lapangan untuk memperkecil wilayah nyata ke ukuran peta.',
                'explanation_video_url' => null,
                'source' => null,
                'status' => 'active',
                'created_by' => $adminUser->id,
            ],
            [
                'subject_id' => 8,
                'topic_id' => 6,
                'class_level' => '12',
                'difficulty' => 'medium',
                'type' => 'multiple_choice',
                'question_text' => 'Menurut Hukum Newton II, hubungan antara gaya, massa, dan percepatan adalah...',
                'question_image' => null,
                'option_a' => 'F = m/a',
                'option_b' => 'F = m × a',
                'option_c' => 'F = a/m',
                'option_d' => 'F = m + a',
                'option_e' => null,
                'correct_answer' => 'b',
                'explanation_text' => 'Hukum Newton II menyatakan bahwa Gaya (F) sama dengan massa (m) dikalikan percepatan (a), atau F = m × a.',
                'explanation_video_url' => null,
                'source' => null,
                'status' => 'active',
                'created_by' => $adminUser->id,
            ],
        ];

        foreach ($questions as $question) {
            Question::updateOrCreate(
                [
                    'subject_id' => $question['subject_id'],
                    'topic_id' => $question['topic_id'],
                    'question_text' => $question['question_text'],
                ],
                $question
            );
        }

        $this->command->info('Questions seeding completed!');
    }
}
