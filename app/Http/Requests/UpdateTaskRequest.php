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
        $task = $this->route('task');
        return [
            'title' => 'sometimes|required|string|max:150',
            'description' => 'nullable|string',
            'priority' => ['sometimes', 'required', Rule::in(['Low', 'Normal', 'High'])],
            'status' => [
                'sometimes', 'required', Rule::in(['Open', 'In Progress', 'Completed', 'Cancel']),
                function ($attribute, $value, $fail) use ($task) {
                    if ($value === 'Completed' && in_array($task->status, ['Open', 'Cancel'])) {
                        $fail('Une tâche ne peut pas passer directement du statut "Ouvert" ou "Différé" à "Terminé".');
                    }
                }
            ],
            'due_date' => 'nullable|date|after_or_equal:today',
            'category_id' => ['sometimes', 'required', 'integer', Rule::exists('categories', 'id')->where('user_id', auth()->id())],
        ];
    }
}
