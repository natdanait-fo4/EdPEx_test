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
        // ล้างข้อมูลเก่าเพื่อรองรับโครงสร้างใหม่ 6 ส่วน
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        \App\Models\AssessmentAnswer::truncate();
        \App\Models\AssessmentQuestion::truncate();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        $questions = [
            // ส่วนที่ 1
            ['category' => 'ส่วนที่ 1: ความพึงพอใจต่อขั้นตอนการให้บริการ', 'question_text' => 'ความสะดวกรวดเร็วในการให้บริการ', 'order' => 1],
            ['category' => 'ส่วนที่ 1: ความพึงพอใจต่อขั้นตอนการให้บริการ', 'question_text' => 'ความถูกต้อง ครบถ้วน ของการให้บริการ', 'order' => 2],
            
            // ส่วนที่ 2
            ['category' => 'ส่วนที่ 2: ความพึงพอใจต่อเจ้าหน้าที่ผู้ให้บริการ', 'question_text' => 'กิริยามารยาท ความสุภาพ และการให้เกียรติผู้ใช้บริการ', 'order' => 3],
            ['category' => 'ส่วนที่ 2: ความพึงพอใจต่อเจ้าหน้าที่ผู้ให้บริการ', 'question_text' => 'ความเอาใจใส่ กระตือรือร้นในการให้บริการ', 'order' => 4],
            ['category' => 'ส่วนที่ 2: ความพึงพอใจต่อเจ้าหน้าที่ผู้ให้บริการ', 'question_text' => 'การให้คำแนะนำ และการตอบข้อซักถามได้ชัดเจนและเป็นประโยชน์', 'order' => 5],
            
            // ส่วนที่ 3
            ['category' => 'ส่วนที่ 3: ความพึงพอใจต่อสิ่งอำนวยความสะดวก', 'question_text' => 'ความเหมาะสมของสถานที่และอุปกรณ์อำนวยความสะดวก', 'order' => 6],
            
            // ส่วนที่ 4
            ['category' => 'ส่วนที่ 4: ความพึงพอใจต่อช่องทางการให้บริการและการประชาสัมพันธ์', 'question_text' => 'ช่องทางการให้บริการมีความสะดวกและเข้าถึงได้ง่าย', 'order' => 7],
            ['category' => 'ส่วนที่ 4: ความพึงพอใจต่อช่องทางการให้บริการและการประชาสัมพันธ์', 'question_text' => 'การประชาสัมพันธ์ข้อมูลข่าวสารมีความชัดเจนและถูกต้อง', 'order' => 8],
            
            // ส่วนที่ 5
            ['category' => 'ส่วนที่ 5: ความพึงพอใจต่อระบบและภาพรวม', 'question_text' => 'ความเสถียรและประสิทธิภาพในการทำงานของระบบ', 'order' => 9],
            ['category' => 'ส่วนที่ 5: ความพึงพอใจต่อระบบและภาพรวม', 'question_text' => 'ความพึงพอใจในภาพรวมต่อการใช้บริการของระบบ', 'order' => 10],
        ];

        foreach ($questions as $q) {
            \App\Models\AssessmentQuestion::create($q);
        }
    }
}
