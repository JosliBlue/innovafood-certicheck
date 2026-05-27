<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        'id_card',
        'full_name',
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

    public function initials(): string
    {
        $parts = preg_split('/\s+/', trim($this->full_name), -1, PREG_SPLIT_NO_EMPTY);

        if ($parts === []) {
            return '?';
        }

        if (count($parts) === 1) {
            return strtoupper(substr($parts[0], 0, 2));
        }

        return strtoupper(substr($parts[0], 0, 1).substr($parts[count($parts) - 1], 0, 1));
    }
}
