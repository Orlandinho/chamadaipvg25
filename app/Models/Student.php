<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    /** @use HasFactory<\Database\Factories\StudentFactory> */
    use HasFactory;

    protected $fillable = ['classroom_id', 'name', 'slug', 'dob', 'inactive'];

    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }

    public function registers(): HasMany
    {
        return $this->hasMany(Register::class);
    }

    protected function casts(): array
    {
        return [
            'dob' => 'datetime'
        ];
    }
}
