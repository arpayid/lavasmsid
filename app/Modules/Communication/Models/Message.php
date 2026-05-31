<?php

namespace App\Modules\Communication\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['sender_id', 'recipient_id', 'subject', 'body', 'is_read'];
    protected $casts = ['is_read' => 'boolean'];

    public function sender() { return $this->belongsTo(\App\Models\User::class, 'sender_id'); }
    public function recipient() { return $this->belongsTo(\App\Models\User::class, 'recipient_id'); }
}
