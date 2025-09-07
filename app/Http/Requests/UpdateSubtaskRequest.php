<?php

namespace App\Http\Requests;

use App\Models\Subtask;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSubtaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $subtask = $this->route('subtask');
        return $subtask && $this->user()->can('update', $subtask);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'sometimes|required|string|max:150',
            'status' => 'sometimes|boolean',
            'order' => 'sometimes|integer',
        ];
    }
}
