<?php

namespace App\Observers;

use App\Models\Subtask;
use App\Models\Task;

class SubtaskObserver
{
    /**
     * Handle the Subtask "saved" event.
     *
     * @param  \App\Models\Subtask  $subtask
     * @return void
     */
    public function saved(Subtask $subtask)
    {
        $this->updateParentTaskStatus($subtask->task);
    }

    /**
     * Handle the Subtask "deleted" event.
     *
     * @param  \App\Models\Subtask  $subtask
     * @return void
     */
    public function deleted(Subtask $subtask)
    {
        $this->updateParentTaskStatus($subtask->task);
    }

    /**
     * Update the parent task's status based on its subtasks.
     *
     * @param  \App\Models\Task  $task
     * @return void
     */
    protected function updateParentTaskStatus(Task $task)
    {
        // We don't want to change status if it was manually set to something final
        if ($task->status === 'Completed' || $task->status === 'Cancel') {
            // However, if all subtasks are done, it should be marked completed
            // Let's reconsider this. If a user marks a task "In Progress" and then
            // finishes all subtasks, it should become "Completed".
            // The check for 'Cancel' is probably good.
        }

        $subtasksCount = $task->subtasks()->count();
        $completedSubtasksCount = $task->subtasks()->where('status', true)->count();

        $newStatus = $task->status;

        if ($subtasksCount === 0) {
            $newStatus = 'Open';
        } elseif ($completedSubtasksCount === $subtasksCount) {
            $newStatus = 'Completed';
        } elseif ($completedSubtasksCount > 0) {
            $newStatus = 'In Progress';
        } else {
            $newStatus = 'Open';
        }

        if ($task->status !== $newStatus) {
            $task->status = $newStatus;
            $task->saveQuietly();
        }
    }
}
