<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Visitant extends Model
{
    /** @use HasFactory<\Database\Factories\VisitantFactory> */
    use HasFactory;

    protected $fillable = ['name','classroom_id','slug'];

    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime'
        ];
    }
}
