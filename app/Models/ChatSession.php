<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ChatSession extends Model
{
    use HasFactory;

    protected $guarded = [];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->chat_session = Str::uuid()->toString();
        });
    }

    public function managedBy()
    {
        return $this->belongsTo('App\Models\User', 'admin_id');
    }
}
