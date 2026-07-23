<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssessmentResponse extends Model
{
    protected $fillable = [
        'suggestion',
        'ip_address',
        'major',
        'user_id',
    ];

    public function answers()
    {
        return $this->hasMany(AssessmentAnswer::class, 'response_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
