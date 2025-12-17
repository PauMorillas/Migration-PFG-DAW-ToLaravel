<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'location',
        'price',
        'duration',
        'business_id'
    ];

    protected $table = 'services';

    public function business()
    {
        return $this->belongsTo(
            Business::class, 'business_id', 'id');
    }
}