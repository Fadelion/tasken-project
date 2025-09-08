import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link, useForm, router } from '@inertiajs/react';
import TextInput from '@/Components/TextInput';
import Pagination from '@/Components/Pagination';
import { useState, useEffect } from 'react';
import { pickBy } from 'lodash';

// Helper function to get color classes based on priority
const priorityClass = (priority) => {
    switch (priority) {
        case 'High':
            return 'border-l-4 border-red-500';
        case 'Normal':
            return 'border-l-4 border-yellow-500';
        case 'Low':
            return 'border-l-4 border-green-500';
        default:
            return 'border-l-4 border-gray-300';
    }
};

// A dedicated Task Card component for better structure
const TaskCard = ({ task, onDelete }) => {
    const { processing } = useForm();
    
    return (
        <div className={`bg-white overflow-hidden shadow-sm rounded-lg p-4 flex flex-col justify-between ${priorityClass(task.priority)}`}>
            <div>
                <div className="flex justify-between items-start">
                    <h3 className="text-lg font-bold text-gray-900">{task.title}</h3>
                    <span className="text-sm font-semibold bg-gray-200 text-gray-700 px-2 py-1 rounded-full">{task.category?.title || 'N/A'}</span>
                </div>
                <p className="text-sm text-gray-600 mt-2">{task.description}</p>
            </div>
            <div className="mt-4 flex justify-between items-center">
                <div className="flex items-center space-x-2">
                    <span className={`px-2 py-1 text-xs font-semibold rounded-full ${task.status === 'Completed' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800'}`}>{task.status}</span>
                    <span className="text-xs text-gray-500">{new Date(task.due_date).toLocaleDateString()}</span>
                </div>
                <div className="flex items-center space-x-2">
                    <Link href={route('tasks.edit', task.id)} className="text-sm text-indigo-600 hover:text-indigo-900 font-medium">Edit</Link>
                    <button onClick={() => onDelete(task.id)} disabled={processing} className="text-sm text-red-600 hover:text-red-900 font-medium">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    );
};


export default function Index({ auth, tasks, filters, success }) {
    const { delete: destroy } = useForm();
    const [search, setSearch] = useState(filters.search || '');

    useEffect(() => {
        const timeout = setTimeout(() => {
            const query = pickBy({ search });
            router.get(route('tasks.index'), query, {
                preserveState: true,
                replace: true,
            });
        }, 300);

        return () => clearTimeout(timeout);
    }, [search]);

    const deleteTask = (id) => {
        if (confirm('Are you sure you want to delete this task?')) {
            destroy(route('tasks.destroy', id), {
                preserveScroll: true,
            });
        }
    };

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Tasks</h2>}
        >
            <Head title="Tasks" />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    {success && (
                        <div className="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span className="block sm:inline">{success}</span>
                        </div>
                    )}
                    <div className="mb-4 flex justify-between items-center">
                        <Link href={route('tasks.create')} className="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow-md">
                            Add Task
                        </Link>
                        <TextInput
                            type="text"
                            name="search"
                            value={search}
                            className="block w-1/3"
                            placeholder="Search tasks..."
                            onChange={(e) => setSearch(e.target.value)}
                        />
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        {tasks.data.map(task => (
                            <TaskCard key={task.id} task={task} onDelete={deleteTask} />
                        ))}
                    </div>

                    {tasks.data.length === 0 && (
                        <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                            <p className="text-center text-gray-500">No tasks found.</p>
                        </div>
                    )}

                    <div className="mt-6">
                        <Pagination links={tasks.links} />
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}

