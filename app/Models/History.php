<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'patient_id',
        'analyse_id',
        'analysis_date',
        'time',
        'status',
        'result',
    ];

    public function patient(){
        return $this->belongsTo(Patient::class);
    }

    public function analyse(){
        return $this->belongsTo(Analyse::class);
    }
}