<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    use HasFactory;

    protected $fillable = ['question_id', 'text', 'text_fr', 'value'];

    public function getTextAttribute($value)
    {
        if (app()->getLocale() === 'fr' && $this->text_fr) {
            return $this->text_fr;
        }
        return $value;
    }
}
