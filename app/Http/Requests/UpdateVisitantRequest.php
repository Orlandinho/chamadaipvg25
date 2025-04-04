<?php

namespace App\Http\Requests;

use App\Models\Visitant;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UpdateVisitantRequest extends FormRequest
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
            'slug' => Str::slug($this->makeSlugFromTitle($this->name))
        ]);
    }

    public function makeSlugFromTitle($name): string
    {
        if ($this->visitant->name !== $name) {

            $slug = Str::slug($name);

            $count = Visitant::whereRaw("slug RLIKE '^{$slug}(-[0-9]+)?$'")->count();

            return $count ? "{$slug}-{$count}" : $slug;
        }

        return $this->visitant->slug;

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
            'slug' => ['required', 'string', 'max:255', Rule::unique('visitants')->ignore($this->visitant->id)],
            'classroom_id' => ['required', 'exists:classrooms,id'],
        ];
    }
}
