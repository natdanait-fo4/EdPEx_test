<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    protected $fillable = [
        'user_id',
        'category',
        'details',
        'status',
        'reply',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
