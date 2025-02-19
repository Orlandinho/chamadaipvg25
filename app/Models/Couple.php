<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Couple extends Model
{
    /** @use HasFactory<\Database\Factories\CoupleFactory> */
    use HasFactory;

    protected $fillable = ['husband', 'wife', 'slug', 'marriage_date'];

    protected function casts(): array
    {
        return [
            'marriage_date' => 'datetime',
        ];
    }
}
