<?php

namespace App\Http\Requests;

use App\Enums\Roles;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class StoreUserRequest extends FormRequest
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
            'slug' => Str::slug($this->makeSlugFromName($this->name)),
            'password' => Hash::make('password'),
        ]);
    }

    public function makeSlugFromName($name): string
    {
        $slug = Str::slug($name);

        $count = User::whereRaw("slug RLIKE '^{$slug}(-[0-9]+)?$'")->count();

        return $count ? "{$slug}-{$count}" : $slug;
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
            'slug' => ['required','string','max:255'],
            'avatar' => ['nullable','image','mimes:jpeg,png,jpg','max:250'],
            'email' => ['required','email','max:255'],
            'role_id' => ['required', Rule::enum(Roles::class)],
            'classroom_id' => ['nullable', Rule::requiredIf($this->role_id === 3), Rule::exists('classrooms', 'id')],
            'password' => ['required','string','min:8'],
        ];
    }
}
