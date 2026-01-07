<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PreBooking extends Model
{
    use SoftDeletes;

    // Esta entidad no usará safe deletes
    protected $table = 'pre_bookings';

    protected $fillable = [
        'token',
        'expiration_date',
        'start_date',
        'end_date',
        'service_id',
        'user_id', // En la otra implementación
        // no usaba el id del cliente pero para esta seguramente lo necesite
        // Voy a mantener esta estructura para facilitar el desarrollo y no tener que cambiar el Javascript,
        // TODO: posible refactor
        'user_name',
        'user_email',
        'user_phone',
        'user_pass',
    ];

    public function service(): BelongsTo {
        return $this->belongsTo(Service::class);
    }
}
