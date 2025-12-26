<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PreBooking extends Model
{
    protected $table = 'pre_bookings';

    protected $fillable = [
        'user_id',
        'token',
        'expiration_date',
        'start_date',
        'end_date',
        // Voy a mantener esta estructura para facilitar el desarrollo y no tener que cambiar el Javascript,
        // TODO: posible refactor
        'user_name',
        'user_email',
        'user_phone',
        'pass_hash',
    ];
}
