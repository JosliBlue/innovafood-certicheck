<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Client extends Model
{
    protected $fillable = [
        'id_card',
        'last_names',
        'first_names',
        'course_name',
        'finished_at',
        'academic_hours',
    ];

    protected function casts(): array
    {
        return [
            'id_card' => 'string',
            'finished_at' => 'date',
            'academic_hours' => 'integer',
        ];
    }

    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn() => "{$this->first_names} {$this->last_names}",
        );
    }
}
