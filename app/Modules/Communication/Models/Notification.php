<?php

namespace App\Modules\Communication\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = ['user_id', 'type', 'title', 'message', 'is_read', 'action_url'];
    protected $casts = ['is_read' => 'boolean'];

    public function user() { return $this->belongsTo(\App\Models\User::class); }

    public function markAsRead(): void { $this->update(['is_read' => true]); }
}
