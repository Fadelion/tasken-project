<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $task = $this->route('task');
        
        if (!$task) {
            return false;
        }
        
        return $this->user()->can('update', $task);
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
            'description' => 'nullable|string',
            'priority' => ['sometimes', 'required', Rule::in(['Low', 'Normal', 'High'])],
            'status' => ['sometimes', 'required', Rule::in(['Open', 'In Progress', 'Completed', 'Deferred'])],
            'due_date' => 'nullable|date',
            'category_id' => ['sometimes', 'required', 'integer', Rule::exists('categories', 'id')->where('user_id', auth()->id())],
        ];
    }
}
