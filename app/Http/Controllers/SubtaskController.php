<?php

namespace App\Http\Controllers;

use App\Models\Subtask;
use App\Http\Requests\StoreSubtaskRequest;
use App\Http\Requests\UpdateSubtaskRequest;
use App\Models\Task;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

class SubtaskController extends Controller
{
    use AuthorizesRequests;
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSubtaskRequest $request)
    {
        $task = Task::find($request->input('task_id'));
        $this->authorize('create', $task);
        try {
            Subtask::create($request->validated());
        } catch (Exception $e) {
            Log::error('Erreur lors de la création de la sous-tâche: '.$e->getMessage());
            return Redirect::back()->withErrors(['msg' => 'Une erreur est survenue lors de l\'ajout de la sous-tâche.']);
        }
        return Redirect::back()->with('success', 'Sous-tâche ajoutée avec succès.');
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSubtaskRequest $request, Subtask $subtask)
    {
        $this->authorize('update', $subtask);
        $task = $subtask->task;
        $validatedData = $request->validated();

        // Logique pour les tâches séquentielles
        if ($task->is_sequential && isset($validatedData['status']) && $validatedData['status'] === true) {
            // Vérifier si toutes les sous-tâches précédentes sont terminées
            $previousSubtasksComplete = Subtask::where('task_id', $task->id)
                                              ->where('order', '<', $subtask->order)
                                              ->where('status', false)
                                              ->doesntExist();

            if (!$previousSubtasksComplete) {
                return Redirect::back()->withErrors(['msg' => 'Veuillez terminer les sous-tâches précédentes avant de marquer celle-ci comme terminée.']);
            }
        }

        try {
            $subtask->update($validatedData);
        } catch (Exception $e) {
            Log::error('Erreur lors de la mise à jour de la sous-tâche: '.$e->getMessage());
            return Redirect::back()->withErrors(['msg' => 'Une erreur est survenue lors de la mise à jour.']);
        }

        return Redirect::back()->with('success', 'Sous-tâche mise à jour.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subtask $subtask)
    {
        $this->authorize('delete', $subtask);
        try {
             $subtask->delete();
        } catch (Exception $e) {
            Log::error('Erreur lors de la suppression de la sous-tâche: '.$e->getMessage());
            return Redirect::back()->withErrors(['msg' => 'Une erreur est survenue lors de la suppression.']);
        }

        return Redirect::back()->with('success', 'Sous-tâche supprimée.');
    }
}
