<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       $tasks = Auth::user()->tasks()->with('category')->latest()->paginate(10);

       return Inertia::render('Tasks/Index', [
        'tasks' => $tasks,
       ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Tasks/Create', [
            'categories' => Auth::user()->categories()->get(['id', 'title']),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        Auth::user()->tasks()->create($request->validated());
        
        return redirect()->route('tasks.index')->with('sucess', 'Tache crée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        $this->authorize('view', $task);
        $task->load(['category', 'subtasks']);

        return Inertia::render('Tasks/Show', [
            'task' => $task,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        $this->authorize('update', $task);

        return Inertia::render('Tasks/Edit', [
            'task' => $task,
            'categories' => Auth::user()->categories()->get(['id', 'title']),
        ]);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        $this->authorize('update', $task);
        $task->update($request->validated());

        return redirect()->route('task.index')->with('success', 'La tache a été mise à jour');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);
        $task->delete();

        return redirect()->route('tasks.index')->with('sucess', 'Tache supprimée')
    }
}
