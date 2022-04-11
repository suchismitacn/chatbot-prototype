<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $casts = [
        'optional_data' => 'array',
    ];

    protected $guarded = [];
}
