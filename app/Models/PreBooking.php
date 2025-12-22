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
        'user_name',
        'user_email',
        'user_phone',
        'pass_hash',
    ];
}