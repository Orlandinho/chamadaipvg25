<?php

namespace App\Http\Resources;

use App\Enums\Bodas;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class CoupleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'husband' => $this->husband,
            'wife' => $this->wife,
            'slug' => $this->slug,
            'husband_avatar' => null,
            'wife_avatar' => null,
            'marriage_date' => $this->marriage_date,
            'bodas' => Bodas::tryFrom(floor(now()->diffInYears($this->marriage_date, true)))?->bodas() ?? 'Ainda não completou um ano'
        ];
    }
}
