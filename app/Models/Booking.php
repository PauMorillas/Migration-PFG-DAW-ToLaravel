<?php

namespace App\Models;

use App\Enums\BookingStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    protected $table = 'bookings';

    protected $fillable = [
        'start_date',
        'end_date',
        'service_id',
        'user_id',
        'status'
    ];

    protected $casts = [
        'status' => BookingStatus::class,
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
}
