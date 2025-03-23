<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class StoreCoupleRequest extends FormRequest
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
        $husband = explode(' ', $this->husband);
        $wife = explode(' ', $this->wife);

        $this->merge([
            'slug' => Str::slug($husband[0] . ' e ' . $wife[0], '_'),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'husband' => ['required','string','max:100'],
            'wife' => ['required','string','max:100'],
            'husband_avatar' => ['nullable','mimes:jpeg,png,jpg','image','max:250'],
            'wife_avatar' => ['nullable','image','mimes:jpeg,png,jpg','max:250'],
            'slug' => ['required','string','max:200','unique:couples,slug'],
            'marriage_date' => ['required','date','before_or_equal:today'],
        ];
    }
}
