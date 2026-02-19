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
        'name_fr',
        'description',
        'description_fr',
        'normal_range',
        'code',
        'price',
        'duration',
        'duration_fr',
        'preparation_instructions',
        'preparation_instructions_fr',
        'image',
        'availability',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'availability' => 'boolean',
    ];

    public function getNameAttribute($value)
    {
        if (app()->getLocale() === 'fr' && $this->name_fr) {
            return $this->name_fr;
        }

        return $value;
    }

    public function getDescriptionAttribute($value)
    {
        if (app()->getLocale() === 'fr' && $this->description_fr) {
            return $this->description_fr;
        }

        return $value;
    }

    public function getDurationAttribute($value)
    {
        if (app()->getLocale() === 'fr' && $this->duration_fr) {
            return $this->duration_fr;
        }

        return $value;
    }

    public function getPreparationInstructionsAttribute($value)
    {
        if (app()->getLocale() === 'fr' && $this->preparation_instructions_fr) {
            return $this->preparation_instructions_fr;
        }

        return $value;
    }

    public function histories()
    {
        return $this->hasMany(History::class);
    }

    public function requestReservations()
    {
        return $this->belongsToMany(Request_reservation::class, 'request_reservation_analyses');
    }

    /**
     * Get the questions for the medical analysis.
     */
    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    /**
     * Get the rules established for this analysis.
     */
    public function analysisRules()
    {
        return $this->hasMany(AnalysisRule::class, 'analysis_id');
    }

    public function reservationAnalyses()
    {
        return $this->hasMany(ReservationAnalysis::class);
    }
}
