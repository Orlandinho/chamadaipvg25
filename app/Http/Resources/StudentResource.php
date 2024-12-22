<?php

namespace App\Http\Resources;

use App\Models\Classroom;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
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
            'dob' => $this->dob,
            'classroom' => $this->whenLoaded('classroom', ClassroomResource::make(Classroom::find($this->classroom_id))),
        ];
    }
}
