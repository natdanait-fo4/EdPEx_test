<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssessmentQuestion extends Model
{
    protected $fillable = [
        'category',
        'question_text',
        'order',
    ];

    public function answers()
    {
        return $this->hasMany(AssessmentAnswer::class, 'question_id');
    }

    /**
     * Helper to sort questions by category priority and order
     */
    public static function sortQuestions($questions)
    {
        $desiredOrder = [
            'สำนักงานคณะวิทยาการจัดการ',
            'หลักสูตร',
            'กองพัฒนานักศึกษา',
            'งานกองทุนกู้ยืมเพื่อการศึกษา',
            'ศูนย์พยาบาลและส่งเสริมสุขภาพ',
            'สำนักวิทยบริการและเทคโนโลยีสารสนเทศ',
            'สำนักส่งเสริมวิชาการและงานทะเบียน'
        ];
        
        return $questions->sortBy(function ($q) use ($desiredOrder) {
            $index = array_search($q->category, $desiredOrder);
            $categoryOrder = $index !== false ? $index : 999;
            return sprintf('%03d-%05d', $categoryOrder, $q->order);
        });
    }
}
