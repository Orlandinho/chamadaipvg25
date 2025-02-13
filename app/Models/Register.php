<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Register extends Model
{
    protected $fillable = ['student_id', 'classroom_id', 'sunday', 'status'];

    /*protected function casts(): array
    {
        return [
            'sunday' => 'datetime'
        ];
    }*/

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
