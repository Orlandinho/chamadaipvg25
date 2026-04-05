<?php

namespace App\Http\Requests;

use App\Enums\Roles;
use App\Models\Student;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

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
        $originalSlug = Str::slug($name);
        $slug = $originalSlug;
        $count = 1;

        while (Student::where('slug', $slug)->exists()) {
            $slug = "{$originalSlug}-{$count}";
            $count++;
        }

        return $slug;
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
            'slug' => ['required','string','max:255', 'unique:users,slug'],
            'avatar' => ['nullable','image','mimes:jpeg,png,jpg','max:250'],
            'email' => ['required','email','max:255', 'unique:users,email'],
            'role_id' => ['required', Rule::enum(Roles::class)],
            'classroom_id' => ['nullable', Rule::requiredIf($this->role_id === 3), Rule::exists('classrooms', 'id')],
            'password' => ['required','string','min:8'],
        ];
    }
}
