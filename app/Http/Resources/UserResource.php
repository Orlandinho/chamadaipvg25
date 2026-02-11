<?php

namespace App\Http\Resources;

use App\Enums\Roles;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class UserResource extends JsonResource
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
            'email' => $this->email,
            'avatar' => null,
            'role' => Roles::tryFrom($this->role_id)->role(),
            'role_id' => $this->when($request->routeIs(['users.index','users.edit']), $this->role_id),
            'classroom' => new ClassroomResource($this->whenLoaded('classroom')),
        ];
    }
}
