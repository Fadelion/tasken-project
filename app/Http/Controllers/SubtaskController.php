<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubtaskRequest;
use App\Http\Requests\UpdateSubtaskRequest;
use App\Models\Subtask;
use App\Models\Task;
use Illuminate\Support\Facades\Gate;
//use Illuminate\Http\Request;

class SubtaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSubtaskRequest $request, Task $task)
    {
        Gate::authorize('update', $task);
        $task->subtasks()->create($request->validated());

        return redirect()->back()->with('success', 'Sous-tâche ajoutée');
    }

    /**
     * Display the specified resource.
     */
    public function show(Subtask $subtask)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Subtask $subtask)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSubtaskRequest $request, Subtask $subtask)
    {
        Gate::authorize('update', $subtask->task);
        $subtask->update($request->validated());

        return redirect()->back()->with('success', 'Sous-tâche mise à jour');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task, Subtask $subtask)
    {
        Gate::authorize('update', $subtask->task);
        $subtask->delete();

        return redirect()->back()->with('success', 'Sous-tâche supprimée');
    }
}
