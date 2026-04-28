<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AssessmentQuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $questions = [
            ['category' => 'ส่วนที่ 1: ความพึงพอใจต่อขั้นตอนการให้บริการ', 'question_text' => 'ความสะดวกรวดเร็วในการให้บริการ', 'order' => 1],
            ['category' => 'ส่วนที่ 1: ความพึงพอใจต่อขั้นตอนการให้บริการ', 'question_text' => 'ความถูกต้อง ครบถ้วน ของการให้บริการ', 'order' => 2],
            ['category' => 'ส่วนที่ 2: ความพึงพอใจต่อเจ้าหน้าที่ผู้ให้บริการ', 'question_text' => 'กิริยามารยาท ความสุภาพ และการให้เกียรติผู้ใช้บริการ', 'order' => 3],
            ['category' => 'ส่วนที่ 2: ความพึงพอใจต่อเจ้าหน้าที่ผู้ให้บริการ', 'question_text' => 'ความเอาใจใส่ กระตือรือร้นในการให้บริการ', 'order' => 4],
            ['category' => 'ส่วนที่ 2: ความพึงพอใจต่อเจ้าหน้าที่ผู้ให้บริการ', 'question_text' => 'การให้คำแนะนำ และการตอบข้อซักถามได้ชัดเจนและเป็นประโยชน์', 'order' => 5],
        ];

        foreach ($questions as $q) {
            \App\Models\AssessmentQuestion::create($q);
        }
    }
}
