<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Analyse extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'normal_range',
        'code',
        'price',
        'duration',
        'preparation_instructions',
        'image',
        'availability'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'availability' => 'boolean',
    ];

    public function histories()
    {
        return $this->hasMany(History::class);
    }

    public function requestReservations()
    {
        return $this->belongsToMany(Request_reservation::class, 'request_reservation_analyses');
    }
}
