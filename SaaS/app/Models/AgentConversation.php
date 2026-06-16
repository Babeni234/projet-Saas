<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AgentConversation extends Model
{
    protected $fillable = ['user_id', 'title', 'messages', 'context'];

    protected function casts(): array
    {
        return [
            'messages' => 'array',
            'context' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
