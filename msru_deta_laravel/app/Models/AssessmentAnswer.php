<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssessmentAnswer extends Model
{
    protected $fillable = [
        'response_id',
        'question_id',
        'score',
    ];

    public function response()
    {
        return $this->belongsTo(AssessmentResponse::class, 'response_id');
    }

    public function question()
    {
        return $this->belongsTo(AssessmentQuestion::class, 'question_id');
    }
}
