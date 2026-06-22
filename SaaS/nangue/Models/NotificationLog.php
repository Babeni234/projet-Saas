<?php

namespace Nangue\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class NotificationLog extends Model
{
    protected $fillable = [
        'type', 'template', 'notifiable_type', 'notifiable_id',
        'user_id', 'recipient', 'subject', 'content', 'status', 'sent_at', 'error_message',
    ];

    protected function casts(): array
    {
        return [
            'sent_at' => 'datetime',
        ];
    }

    public function notifiable() { return $this->morphTo(); }
    public function user() { return $this->belongsTo(User::class); }
}
