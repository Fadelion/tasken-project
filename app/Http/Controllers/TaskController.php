<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Inertia\Inertia;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Exception;


class TaskController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $user = Auth::user();
        $filters = request()->only(['search']);
        $tasks = Task::where('user_id', $user->id)
            ->when(request('search'), function ($query, $search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            })
            ->with('category', 'subtasks')
            ->withCount(['subtasks', 'completedSubtasks'])
            ->latest()
            ->paginate(9)
            ->appends(request()->input());

        return Inertia::render('Tasks/Index', [
            'tasks' => $tasks,
            'filters' => $filters,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Inertia\Response
     */
    public function create()
    {
        $categories = Auth::user()->categories;
        return Inertia::render('Tasks/Create', [
            'categories' => $categories
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StoreTaskRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreTaskRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $validated = $request->validated();
                $taskData = collect($validated)->except('subtasks')->all();

                $task = new Task($taskData);
                $task->user_id = Auth::id();
                $task->save();

                if (isset($validated['subtasks'])) {
                    foreach ($validated['subtasks'] as $subtaskData) {
                        $task->subtasks()->create([
                            'title' => $subtaskData['title'],
                            'status' => false, // Default status for new subtasks
                        ]);
                    }
                }
            });
        } catch (Exception $e) {
            Log::error('Erreur lors de la création de la tâche: '.$e->getMessage());
            return Redirect::route('tasks.index')->with('error', 'Une erreur est survenue lors de la création de la tâche.');
        }

        return Redirect::route('tasks.index')->with('success', 'Tâche et sous-tâches créées avec succès.');
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Task $task
     * @return \Inertia\Response
     */
    public function show(Task $task)
    {
        $this->authorize('view', $task);

        $task->load(['category', 'subtasks' => function ($query) {
            $query->orderBy('created_at', 'asc');
        }]);

        return Inertia::render('Tasks/Show', [
            'task' => $task,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Task $task
     * @return \Inertia\Response
     */
    public function edit(Task $task)
    {
        $this->authorize('update', $task);
        $categories = Auth::user()->categories;
        $subtasks = $task->subtasks()->orderBy('order')->get();

        return Inertia::render('Tasks/Edit', [
            'task' => $task,
            'categories' => $categories,
            'subtasks' => $subtasks,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UpdateTaskRequest $request
     * @param \App\Models\Task $task
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        $this->authorize('update', $task);
        try {
            $task->update($request->validated());
        } catch (Exception $e) {
            Log::error('Erreur lors de la mise à jour de la tâche: '.$e->getMessage());
            return Redirect::back()->withErrors(['msg' => 'Une erreur est survenue lors de la mise à jour.']);
        }

        return Redirect::route('tasks.index')->with('success', 'Tâche mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Task $task
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);
        try {
            $task->delete();
        } catch (Exception $e) {
            Log::error('Erreur lors de la suppression de la tâche: '.$e->getMessage());
            return Redirect::route('tasks.index')->withErrors(['msg' => 'Une erreur est survenue lors de la suppression.']);
        }
        return Redirect::route('tasks.index')->with('success', 'Tâche supprimée avec succès.');
    }
}
