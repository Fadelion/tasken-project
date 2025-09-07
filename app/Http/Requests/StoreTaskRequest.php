<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' =>'required|string|max:150',
            'description' => 'nullable|string',
            'priority' => ['required', Rule::in(['faible', 'moyenne', 'elevee'])],
            'status' => ['required', Rule::in(['a_faire', 'en_cours', 'terminee'])],
            'due_date' => 'nullable|date',
            'category_id' => ['required', 'integer', Rule::exists('categories', 'id')->where('user_id', auth()->id())]
            
        ];
    }
}
