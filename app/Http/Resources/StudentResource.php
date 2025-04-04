<?php

namespace App\Http\Resources;

use App\Models\Classroom;
use App\Models\Register;
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
            'contact' => $this->contact,
            'avatar' => $this->avatar ? asset('storage/' . $this->avatar) : null,
            'inactive' => (boolean) $this->inactive,
            'classroom' => ClassroomResource::make($this->whenLoaded('classroom')),
            'registers' => RegisterResource::collection($this->whenLoaded('registers')),
            'classes' => $this->registers()->count(),
            'frequency' => $this->registers()->where('status', 1)->count(),
            'frequency_ratio' => $this->when($request->routeIs('students.show'), $this->frequency_rate($this->registers()->count(), $this->registers()->where('status', 1)->count())),
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
