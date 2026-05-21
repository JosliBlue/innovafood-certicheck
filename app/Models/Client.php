<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        'id_card',
        'last_names',
        'first_names',
        'course_name',
        'finished_at',
        'academic_hours',
        'certificate_printed',
    ];

    protected function casts(): array
    {
        return [
            'id_card' => 'string',
            'finished_at' => 'date',
            'academic_hours' => 'integer',
            'certificate_printed' => 'boolean',
        ];
    }

    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn () => "{$this->first_names} {$this->last_names}",
        );
    }
}
