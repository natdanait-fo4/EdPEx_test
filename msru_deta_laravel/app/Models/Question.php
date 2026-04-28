<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    const UPDATED_AT = null;
    protected $fillable = ['user_id', 'title', 'details', 'status', 'answer', 'privacy', 'notify_email'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
