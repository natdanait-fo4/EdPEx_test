<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    protected $fillable = [
        'q1_1',
        'q1_2',
        'q2_1',
        'q2_2',
        'q2_3',
        'suggestion',
    ];
}
