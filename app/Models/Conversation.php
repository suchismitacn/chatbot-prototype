<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $casts = [
        'optional_data' => 'array',
    ];

    protected $guarded = [];


    /**
     * Get message owner details.
     */
    public function sender()
    {
        return $this->belongsTo('App\Models\User', 'sender_id', 'id');
    }

    /**
     * Get message owner details.
     */
    public function receiver()
    {
        return $this->belongsTo('App\Models\User', 'recipient_id', 'id');
    }
}
