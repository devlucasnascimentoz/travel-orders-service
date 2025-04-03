<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TravelOrder extends Model
{
    protected $fillable = [
        'user_id',
        'requester_name',
        'destination',
        'start_date',
        'end_date',
        'status'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function canBeCancelled(): bool
    {
        return $this->status === 'aprovado'
            && $this->start_date > now()->addDays(3);
    }
}
