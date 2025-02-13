<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Classroom extends Model
{
    /** @use HasFactory<\Database\Factories\ClassroomFactory> */
    use HasFactory;

    protected $fillable = ['name', 'slug', 'description'];

    public function teachers(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    public function visitants(): HasMany
    {
        return $this->hasMany(Visitant::class);
    }

    public function registers(): HasMany
    {
        return $this->hasMany(Register::class);
    }
}
