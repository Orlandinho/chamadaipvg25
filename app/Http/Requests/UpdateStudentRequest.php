<?php

namespace App\Http\Requests;

use App\Models\Student;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UpdateStudentRequest extends FormRequest
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
        if ($this->student->name !== $name) {

            $slug = Str::slug($name);

            $count = Student::whereRaw("slug RLIKE '^{$slug}(-[0-9]+)?$'")->count();

            return $count ? "{$slug}-{$count}" : $slug;
        }

        return $this->student->slug;

    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', Rule::unique('students')->ignore($this->student->id)],
            'dob' => ['required', 'date', 'date_format:Y-m-d', 'before_or_equal:today'],
            'classroom_id' => ['nullable', 'integer', 'exists:classrooms,id'],
            'inactive' => ['boolean'],
        ];
    }
}
