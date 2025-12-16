<?php
namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Business extends Model {
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id',
        'name',
        'email',
        'phone',
        'open_hours',
        'close_hours',
        'open_days',
        'user_id'
    ];

    protected $table = 'business';

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}