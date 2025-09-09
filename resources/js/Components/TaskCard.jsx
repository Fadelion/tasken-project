import React, { useState } from 'react';
import PrimaryButton from './PrimaryButton';
import SecondaryButton from './SecondaryButton';
import { router, Link } from '@inertiajs/react';
import { toast } from 'react-hot-toast';

export default function TaskCard({ task }) {
    const [expanded, setExpanded] = useState(false);

    // Utilise la valeur calculée par le backend
    const progress = task.progress_percentage;
    const completedSubtasks = task.completed_subtasks_count;

    const handleDelete = () => {
        if (window.confirm('Êtes-vous sûr de vouloir supprimer cette tâche ?')) {
            router.delete(route('tasks.destroy', task.id), {
                preserveScroll: true,
                onSuccess: () => toast.success('Tâche supprimée avec succès !'),
                onError: () => toast.error('Erreur lors de la suppression.'),
            });
        }
    };

    // Gère le changement de statut avec une valeur booléenne
    const handleSubtaskStatusChange = (subtask, newStatus) => {
        router.patch(route('subtasks.update', { subtask: subtask.id }), {
            status: newStatus // Envoie true ou false directement
        }, {
            preserveScroll: true,
            onSuccess: () => {
                router.reload({ only: ['tasks'], preserveScroll: true });
                toast.success('Statut de la sous-tâche mis à jour !');
            },
            onError: (errors) => {
                const errorMessage = errors.msg || 'Une erreur est survenue.';
                toast.error(errorMessage);
                // On recharge même en cas d'erreur pour resynchroniser l'état
                router.reload({ only: ['tasks'], preserveScroll: true });
            }
        });
    };

    const isSubtaskDisabled = (subtask, index) => {
        if (!task.is_sequential) {
            return false;
        }
        for (let i = 0; i < index; i++) {
            // Vérifie si les précédentes sont terminées (status === true)
            if (task.subtasks[i].status !== true) {
                return true;
            }
        }
        return false;
    };


    return (
        <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4">
            <div className="p-6">
                <div className="flex justify-between items-start">
                    <div>
                        <h3 className="text-lg font-semibold text-gray-800">{task.title}</h3>
                        <p className="text-sm text-gray-500">
                            Échéance: {new Date(task.due_date).toLocaleDateString('fr-FR')} - Priorité: <span className="capitalize">{task.priority}</span>
                        </p>
                    </div>
                    <div className="flex space-x-2 flex-shrink-0">
                        <Link href={route('tasks.edit', task.id)}>
                            <SecondaryButton>Modifier</SecondaryButton>
                        </Link>
                        <PrimaryButton onClick={() => setExpanded(!expanded)}>
                            {expanded ? 'Réduire' : 'Détails'}
                        </PrimaryButton>
                    </div>
                </div>

                {expanded && (
                    <div className="mt-4">
                        <p className="text-gray-600 mb-4">{task.description}</p>

                        {task.subtasks.length > 0 && (
                            <div>
                                <span className="text-sm font-medium text-gray-700">Progression</span>
                                <div className="w-full bg-gray-200 rounded-full h-2.5 mt-1">
                                    <div className="bg-blue-600 h-2.5 rounded-full" style={{ width: `${progress}%` }}></div>
                                </div>
                                <span className="text-xs text-gray-500">{completedSubtasks} / {task.subtasks.length} sous-tâches terminées</span>
                            </div>
                        )}

                        <div className="mt-4">
                            <h4 className="font-semibold text-gray-700">Sous-tâches :</h4>
                            <ul className="list-disc list-inside mt-2 space-y-2">
                                {task.subtasks.map((subtask, index) => (
                                    <li key={subtask.id} className="flex items-center">
                                        <input
                                            type="checkbox"
                                            checked={subtask.status} // Directement lié à la valeur booléenne
                                            onChange={(e) => handleSubtaskStatusChange(subtask, e.target.checked)} // Envoie la nouvelle valeur booléenne
                                            className="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 disabled:opacity-50"
                                            disabled={isSubtaskDisabled(subtask, index)}
                                        />
                                        <span className={`ms-2 ${subtask.status ? 'line-through text-gray-500' : 'text-gray-800'}`}>
                                            {subtask.title}
                                        </span>
                                    </li>
                                ))}
                                {task.subtasks.length === 0 && (
                                    <p className="text-sm text-gray-500">Aucune sous-tâche pour cette tâche.</p>
                                )}
                            </ul>
                        </div>
                        <div className="mt-6 flex justify-end">
                            <button onClick={handleDelete} className="text-red-600 hover:text-red-800 text-sm font-medium">
                                Supprimer la tâche
                            </button>
                        </div>
                    </div>
                )}
            </div>
        </div>
    );
}

