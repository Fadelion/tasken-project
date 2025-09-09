import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, useForm, Link } from "@inertiajs/react";
import InputLabel from "@/Components/InputLabel";
import TextInput from "@/Components/TextInput";
import InputError from "@/Components/InputError";
import PrimaryButton from "@/Components/PrimaryButton";
import SecondaryButton from "@/Components/SecondaryButton";

export default function Create({ auth, categories }) {
    const { data, setData, post, processing, errors } = useForm({
        title: '',
        description: '',
        category_id: categories.length > 0 ? categories[0].id : '',
        priority: 'Normal',
        status: 'Open',
        due_date: '',
        subtasks: [], // Array to hold subtasks
    });

    // Add a new subtask input field
    const addSubtask = () => {
        setData('subtasks', [...data.subtasks, { title: '' }]);
    };

    // Remove a subtask input field by index
    const removeSubtask = (index) => {
        const newSubtasks = [...data.subtasks];
        newSubtasks.splice(index, 1);
        setData('subtasks', newSubtasks);
    };

    // Handle changes in subtask titles
    const handleSubtaskChange = (index, value) => {
        const newSubtasks = [...data.subtasks];
        newSubtasks[index].title = value;
        setData('subtasks', newSubtasks);
    };

    const submit = (e) => {
        e.preventDefault();
        post(route('tasks.store'));
    };

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Créer une nouvelle tâche</h2>}
        >
            <Head title="Créer une tâche" />

            <div className="py-12">
                <div className="max-w-3xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-8">
                            <form onSubmit={submit}>
                                {/* Task Title */}
                                <div className="mb-6">
                                    <InputLabel htmlFor="title" value="Titre" />
                                    <TextInput
                                        id="title"
                                        value={data.title}
                                        onChange={(e) => setData('title', e.target.value)}
                                        className="mt-1 block w-full"
                                        isFocused
                                    />
                                    <InputError message={errors.title} className="mt-2" />
                                </div>

                                {/* Task Description */}
                                <div className="mb-6">
                                    <InputLabel htmlFor="description" value="Description" />
                                    <textarea
                                        id="description"
                                        value={data.description}
                                        onChange={(e) => setData('description', e.target.value)}
                                        className="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                        rows="4"
                                    ></textarea>
                                    <InputError message={errors.description} className="mt-2" />
                                </div>

                                <div className="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                    {/* Category */}
                                    <div>
                                        <InputLabel htmlFor="category_id" value="Catégorie" />
                                        <select
                                            id="category_id"
                                            value={data.category_id}
                                            onChange={(e) => setData('category_id', e.target.value)}
                                            className="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                        >
                                            {categories.map(cat => <option key={cat.id} value={cat.id}>{cat.title}</option>)}
                                        </select>
                                        <InputError message={errors.category_id} className="mt-2" />
                                    </div>
                                    {/* Due Date */}
                                    <div>
                                        <InputLabel htmlFor="due_date" value="Date d'échéance" />
                                        <TextInput
                                            id="due_date"
                                            type="date"
                                            value={data.due_date}
                                            onChange={(e) => setData('due_date', e.target.value)}
                                            className="mt-1 block w-full"
                                        />
                                        <InputError message={errors.due_date} className="mt-2" />
                                    </div>
                                    {/* Priority */}
                                    <div>
                                        <InputLabel htmlFor="priority" value="Priorité" />
                                        <select
                                            id="priority"
                                            value={data.priority}
                                            onChange={(e) => setData('priority', e.target.value)}
                                            className="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                        >
                                            <option value="Low">Faible</option>
                                            <option value="Normal">Normal</option>
                                            <option value="High">Élevée</option>
                                        </select>
                                        <InputError message={errors.priority} className="mt-2" />
                                    </div>
                                    {/* Status */}
                                    <div>
                                        <InputLabel htmlFor="status" value="Statut" />
                                        <select
                                            id="status"
                                            value={data.status}
                                            onChange={(e) => setData('status', e.target.value)}
                                            className="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                        >
                                            <option value="Open">À faire</option>
                                            <option value="In Progress">En cours</option>
                                            <option value="Completed">Terminé</option>
                                            <option value="Cancel">Annulé</option>
                                        </select>
                                        <InputError message={errors.status} className="mt-2" />
                                    </div>
                                </div>

                                {/* Subtasks Section */}
                                <div className="mb-6">
                                    <h3 className="text-lg font-medium text-gray-900 mb-2">Sous-tâches</h3>
                                    <div className="space-y-4">
                                        {data.subtasks.map((subtask, index) => (
                                            <div key={index} className="flex items-center space-x-2">
                                                <TextInput
                                                    type="text"
                                                    value={subtask.title}
                                                    onChange={(e) => handleSubtaskChange(index, e.target.value)}
                                                    className="block w-full"
                                                    placeholder={`Sous-tâche ${index + 1}`}
                                                />
                                                <button type="button" onClick={() => removeSubtask(index)} className="text-red-500 hover:text-red-700">
                                                    Supprimer
                                                </button>
                                            </div>
                                        ))}
                                    </div>
                                    <SecondaryButton type="button" onClick={addSubtask} className="mt-4">
                                        Ajouter une sous-tâche
                                    </SecondaryButton>
                                </div>

                                <div className="flex items-center justify-end space-x-4">
                                    <Link href={route('tasks.index')} className="text-gray-600">Annuler</Link>
                                    <PrimaryButton disabled={processing}>
                                        Créer la tâche
                                    </PrimaryButton>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}