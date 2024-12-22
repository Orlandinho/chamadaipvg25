<?php

namespace App\Http\Requests;

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
    public function prepareForValidation(): void
    {
        if ($this->name !== $this->student->name) {
            $this->merge([
                'slug' => Str::slug($this->name, '-')
            ]);
        } else {
            $this->merge([
                'slug' => $this->student->slug
            ]);
        }

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
        ];
    }
}
