<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClassroomResource extends JsonResource
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
            'description' => $this->description,
            'teachers' => UserResource::collection($this->whenLoaded('teachers')),
            'students_count' => $this->whenCounted('active_students'),
            'frequency_ratio' => $this->when($request->routeIs(['classrooms.index','dashboard']), $this->frequency_rate($this->registers()->count(), $this->registers()->where('status', 1)->count())),
        ];
    }

    protected function frequency_rate(int $classes, int $frequency): int | float | string
    {
        if ($classes < 1) {
            return 'Não definido';
        }

        return round(($frequency / $classes) * 100, 2) . '%';
    }
}
