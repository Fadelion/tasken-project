<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
      $tasks = Auth::user()->tasks()
            ->with('category')
            // Use withCount for efficient subtask counting
            ->withCount(['subtasks', 'completedSubtasks'])
            ->when($request->input('search'), function ($query, $search) {
                $query->where('title', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Tasks/Index', [
            'tasks' => $tasks,
            'filters' => $request->only(['search']),
            'success' => session('success'),
        ]); 
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Auth::user()->categories()->get(['id', 'title']);

        return Inertia::render('Tasks/Create', [
            'categories' => $categories,
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
        
        Gate::authorize('update', $task);
        $categories = Auth::user()->categories()->get(['id', 'title']);

        // Eager load subtasks for the edit page
        $task->load('subtasks');

        return Inertia::render('Tasks/Edit', [
            'task' => $task,
            'categories' => $categories,
            'subtasks' => $task->subtasks,
        ]);
        

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        Gate::authorize('update', $task);
        $task->update($request->validated());

        return redirect()->route('tasks.index')->with('success', 'La tache a été mise à jour');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        Gate::authorize('update', $task);
        $task->delete();

        return redirect()->route('tasks.index')->with('sucess', 'Tache supprimée');
    }
}
