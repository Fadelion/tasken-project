<?php

namespace App\Http\Requests;

use App\Models\Task;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreSubtaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function rules(): array
    {
        return [
            'task_id' => [
                'required',
                'integer',
                'exists:tasks,id',
                function ($attribute, $value, $fail) {
                    $task = Task::find($value);
                    if (!$task || $task->user_id !== auth()->id()) {
                        $fail('La tâche sélectionnée n\'existe pas ou ne vous appartient pas.');
                    }
                },
            ],
            'title' => 'required|string|max:150',
            'status' => 'sometimes|boolean',
            'order' => 'sometimes|integer',
        ];
    }

    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
}
