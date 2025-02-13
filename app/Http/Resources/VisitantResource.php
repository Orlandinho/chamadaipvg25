<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VisitantResource extends JsonResource
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
            'name' => $this->name,
            'slug' => $this->slug,
            'classroom_id' => $this->classroom_id,
            'visit' => $this->created_at->format('d/m/Y'),
            'classroom' => ClassroomResource::make($this->whenLoaded('classroom')),
        ];
    }
}
