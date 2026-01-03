<?php

namespace App\Http\Requests;

use App\Models\Couple;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UpdateCoupleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function makeSlugFromCouple(string $husband, string $wife): string
    {
        $originalSlug = Str::slug(explode(' ', $husband)[0] . ' e ' . explode(' ', $wife)[0], '_');

        if($originalSlug === $this->couple->slug) {
            return $this->couple->slug;
        }

        $slug = $originalSlug;
        $count = 1;

        while (Couple::where('slug', $slug)->exists()) {
            $slug = "{$originalSlug}-{$count}";
            $count++;
        }

        return $slug;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'slug' => $this->makeSlugFromCouple($this->husband, $this->wife),
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
            'slug' => ['required','string','max:200', Rule::unique('couples')->ignore($this->couple->id)],
            'marriage_date' => ['required','date','before_or_equal:today'],
        ];
    }
}
