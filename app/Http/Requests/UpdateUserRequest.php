<?php

namespace App\Http\Requests;

use App\Enums\Roles;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'slug' => Str::slug($this->makeSlugFromName($this->name))
        ]);
    }

    public function makeSlugFromName($name): string
    {
        if ($this->user->name !== $name) {

            $slug = Str::slug($name);

            $count = User::whereRaw("slug RLIKE '^{$slug}(-[0-9]+)?$'")->count();

            return $count ? "{$slug}-{$count}" : $slug;
        }

        return $this->user->slug;

    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required','string','max:255'],
            'slug' => ['required','string','max:255', Rule::unique('users')->ignore($this->user->id)],
            'email' => ['required','email','max:255', Rule::unique('users')->ignore($this->user->id)],
            'role_id' => ['required', Rule::enum(Roles::class)],
            'classroom_id' => ['nullable', Rule::exists('classrooms', 'id')],
        ];
    }
}
