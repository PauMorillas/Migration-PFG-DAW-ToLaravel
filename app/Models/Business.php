<?php
namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Business extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'open_hours',
        'close_hours',
        'open_days',
        'user_id'
    ];

    protected $table = 'businesses';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
        // Eloquent sabe, por convención, que esta relación pertenece al modelo User
        // y que la clave foránea en esta tabla (businesses) es 'user_id' que apunta al 'id' del usuario
        // Igualmente lo especifico para entender que es lo que esta haciendo para generar las sql
    }

    public function services()
    {
        return $this->hasMany(Service::class);
    }
}