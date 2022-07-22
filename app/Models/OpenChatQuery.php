<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpenChatQuery extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function chatSession()
    {
        return $this->belongsTo('App\Models\ChatSession');
    }

    public function attendedBy()
    {
        return $this->belongsTo('App\Models\User', 'attended_by');
    }
}
