import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, useForm } from '@inertiajs/react';
import InputLabel from '@/Components/InputLabel';
import TextInput from '@/Components/TextInput';
import InputError from '@/Components/InputError';
import PrimaryButton from '@/Components/PrimaryButton';

export default function Edit({ auth, task, categories }) {
    const { data, setData, patch, processing, errors } = useForm({
        title: task.title,
        description: task.description || '',
        category_id: task.category_id,
        priority: task.priority,
        status: task.status,
        due_date: task.due_date || '',
    });

    const submit = (e) => {
        e.preventDefault();
        patch(route('tasks.update', task.id));
    };

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight"></h2>}
        >
            <Head title="" />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6 text-gray-900">
                            <form onSubmit={submit}>
                              

                                <PrimaryButton disabled={processing}>Mettre à jour</PrimaryButton>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}


import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, useForm, usePage } from '@inertiajs/react';
import InputLabel from '@/Components/InputLabel';
import TextInput from '@/Components/TextInput';
import InputError from '@/Components/InputError';
import PrimaryButton from '@/Components/PrimaryButton';
import DangerButton from '@/Components/DangerButton';

export default function Edit({ auth, task, categories, subtasks }) {
    const { data, setData, patch, processing, errors } = useForm({
        title: task.title,
        description: task.description || '',
        category_id: task.category_id,
        priority: task.priority,
        status: task.status,
        due_date: task.due_date || '',
    });

    const { data: subtaskData, setData: setSubtaskData, post: postSubtask, processing: subtaskProcessing, errors: subtaskErrors, reset: resetSubtaskForm } = useForm({
        title: '',
    });
    
    const { delete: destroySubtask } = useForm();

    const { success } = usePage().props;

    const submit = (e) => {
        e.preventDefault();
        patch(route('tasks.update', task.id));
    };

    const handleAddSubtask = (e) => {
        e.preventDefault();
        postSubtask(route('tasks.subtasks.store', task.id), {
            onSuccess: () => resetSubtaskForm(),
        });
    };
    
    const handleDeleteSubtask = (subtaskId) => {
        if(confirm('Etes-vous sûr de vouloir supprimer la sous-tâche ?')) {
            destroySubtask(route('subtasks.destroy', subtaskId));
        }
    }

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Modifier une tâche</h2>}
        >
            <Head title="Modification de tâche" />
            
            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                    {success && (
                        <div className="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                            <span className="block sm:inline">{success}</span>
                        </div>
                    )}
                    {/* Formulaire d'édition de la tâche */}
                    <div className="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                        <h3 className="text-lg font-medium text-gray-900 mb-4">Détails de la tâche</h3>
                        <form onSubmit={submit}>
                            {/* Formulaire pour les champs de la tâche*/}
                              {/* Titre */}
                                <div className="mb-4">
                                    <InputLabel htmlFor="title" value="Title" />
                                    <TextInput id="title" name="title" value={data.title} className="mt-1 block w-full" onChange={(e) => setData('title', e.target.value)} />
                                    <InputError message={errors.title} className="mt-2" />
                                </div>

                                {/* Categorie */}
                                <div className="mb-4">
                                    <InputLabel htmlFor="category_id" value="Category" />
                                    <select id="category_id" name="category_id" value={data.category_id} className="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" onChange={(e) => setData('category_id', e.target.value)}>
                                        {categories.map(category => (
                                            <option key={category.id} value={category.id}>{category.title}</option>
                                        ))}
                                    </select>
                                    <InputError message={errors.category_id} className="mt-2" />
                                </div>

                                {/* Description */}
                                <div className="mb-4">
                                    <InputLabel htmlFor="description" value="Description" />
                                    <textarea id="description" name="description" value={data.description} className="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" onChange={(e) => setData('description', e.target.value)} />
                                    <InputError message={errors.description} className="mt-2" />
                                </div>

                                {/* Priorité */}
                                <div className="mb-4">
                                    <InputLabel htmlFor="priority" value="Priority" />
                                    <select id="priority" name="priority" value={data.priority} className="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" onChange={(e) => setData('priority', e.target.value)}>
                                        <option value="Low">Faible</option>
                                        <option value="Normal">Normal</option>
                                        <option value="High">Elevé</option>
                                    </select>
                                    <InputError message={errors.priority} className="mt-2" />
                                </div>

                                {/* Statut */}
                                <div className="mb-4">
                                    <InputLabel htmlFor="status" value="Status" />
                                    <select id="status" name="status" value={data.status} className="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" onChange={(e) => setData('status', e.target.value)}>
                                        <option value="Open">A Faire</option>
                                        <option value="In Progress">En Cours</option>
                                        <option value="Completed">Terminé</option>
                                        <option value="Deferred">Annulé</option>
                                    </select>
                                    <InputError message={errors.status} className="mt-2" />
                                </div>

                                {/* Date d'échéance */}
                                <div className="mb-4">
                                    <InputLabel htmlFor="due_date" value="Due Date" />
                                    <TextInput id="due_date" type="date" name="due_date" value={data.due_date} className="mt-1 block w-full" onChange={(e) => setData('due_date', e.target.value)} />
                                    <InputError message={errors.due_date} className="mt-2" />
                                </div>
                            <PrimaryButton disabled={processing}>Mettre à jour</PrimaryButton>
                        </form>
                    </div>

                    {/* Section des sous-tâches */}
                    <div className="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                        <h3 className="text-lg font-medium text-gray-900 mb-4">Subtasks</h3>
                        
                        {/* Formulaire des sous-tâches */}
                        <form onSubmit={handleAddSubtask} className="mb-6">
                             <div className="mb-4">
                                <InputLabel htmlFor="subtask_title" value="New Subtask Title" />
                                <TextInput id="subtask_title" value={subtaskData.title} className="mt-1 block w-full" onChange={(e) => setSubtaskData('title', e.target.value)} />
                                <InputError message={subtaskErrors.title} className="mt-2" />
                            </div>
                            <PrimaryButton disabled={subtaskProcessing}>Ajouter</PrimaryButton>
                        </form>

                        {/* Liste des sous-tâches */}
                        <div className="space-y-2">
                           {subtasks.map(subtask => (
                               <div key={subtask.id} className="flex items-center justify-between p-2 border rounded">
                                   <span>{subtask.title}</span>
                                   <DangerButton onClick={() => handleDeleteSubtask(subtask.id)}>Supprimer</DangerButton>
                               </div>
                           ))}
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}

