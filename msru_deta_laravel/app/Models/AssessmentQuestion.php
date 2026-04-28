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
}
