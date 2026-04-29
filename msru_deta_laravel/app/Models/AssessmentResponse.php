<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssessmentResponse extends Model
{
    protected $fillable = [
        'suggestion',
        'ip_address',
    ];

    public function answers()
    {
        return $this->hasMany(AssessmentAnswer::class, 'response_id');
    }
}
