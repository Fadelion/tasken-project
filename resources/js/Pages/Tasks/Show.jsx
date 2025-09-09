import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link, useForm } from '@inertiajs/react';
import Checkbox from '@/Components/Checkbox';
import PrimaryButton from '@/Components/PrimaryButton';
import { router } from '@inertiajs/react';

export default function Show({ auth, task }) {

    const { data, setData, patch, processing, errors } = useForm({
        status: task.status,
    });

    const handleSubtaskChange = (subtask, completed) => {
        router.patch(route('subtasks.update', subtask.id), {
            status: completed,
        }, {
            preserveScroll: true,
        });
    };

    const markTaskAsCompleted = () => {
        patch(route('tasks.update', task.id), {
            status: 'Completed'
        });
    };

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Task Details</h2>}
        >
            <Head title="Task Details" />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6 bg-white border-b border-gray-200">
                            <div className="mb-4">
                                <Link href={route('tasks.index')} className="text-blue-600 hover:text-blue-900">
                                    &larr; Back to all tasks
                                </Link>
                            </div>
                            <h1 className="text-3xl font-bold mb-2">{task.title}</h1>
                            <div className="flex items-center space-x-2 mb-4">
                                <span className={`px-2 py-1 text-xs font-semibold rounded-full ${task.status === 'Completed' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800'}`}>{task.status}</span>
                                <span className="text-sm text-gray-500">Category: {task.category?.title || 'N/A'}</span>
                                {task.due_date && <span className="text-sm text-gray-500">Due: {new Date(task.due_date).toLocaleDateString()}</span>}
                            </div>
                            <p className="text-gray-700 mb-6">{task.description}</p>

                            <h3 className="text-xl font-bold mb-4">Subtasks</h3>
                            <div className="space-y-2">
                                {task.subtasks.map((subtask) => (
                                    <div key={subtask.id} className="flex items-center">
                                        <Checkbox
                                            name={`subtask-${subtask.id}`}
                                            checked={subtask.status}
                                            onChange={(e) => handleSubtaskChange(subtask, e.target.checked)}
                                        />
                                        <span className={`ms-2 ${subtask.status ? 'line-through text-gray-500' : ''}`}>{subtask.title}</span>
                                    </div>
                                ))}
                                {task.subtasks.length === 0 && <p className="text-gray-500">No subtasks for this task.</p>}
                            </div>

                            {task.status !== 'Completed' && (
                                <div className="mt-6">
                                    <PrimaryButton onClick={markTaskAsCompleted} disabled={processing}>
                                        Mark Task as Completed
                                    </PrimaryButton>
                                </div>
                            )}
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
