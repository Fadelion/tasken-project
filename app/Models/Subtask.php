<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subtask extends Model
{
    /** @use HasFactory<\Database\Factories\SubtaskFactory> */
    use HasFactory;

    protected $fillable =[
        'title',
        'status',
        'order',
        'task_id',
    ];

    /**
     * Get the task that owns the subtask.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function task() {
        return $this->belongsTo(Task::class);
    }
}
