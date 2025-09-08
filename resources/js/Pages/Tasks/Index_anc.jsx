import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link, useForm, router } from '@inertiajs/react';
import PrimaryButton from '@/Components/PrimaryButton';
import TextInput from '@/Components/TextInput';
import Pagination from '@/Components/Pagination';
import { useState, useEffect } from 'react';
import { pickBy } from 'lodash';

export default function Index({ auth, tasks, filters, success }) {
    const { delete: destroy, processing } = useForm();
    const [search, setSearch] = useState(filters.search || '');

    useEffect(() => {
        // Debounce search input
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
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6 text-gray-900">
                            <div className="flex justify-between items-center mb-4">
                                <Link href={route('tasks.create')} className="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
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
                            <table className="min-w-full divide-y divide-gray-200">
                                <thead className="bg-gray-50">
                                    <tr>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priority</th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody className="bg-white divide-y divide-gray-200">
                                    {tasks.data.map(task => (
                                        <tr key={task.id}>
                                            <td className="px-6 py-4 whitespace-nowrap">{task.title}</td>
                                            <td className="px-6 py-4 whitespace-nowrap">{task.category ? task.category.title : 'N/A'}</td>
                                            <td className="px-6 py-4 whitespace-nowrap">{task.status}</td>
                                            <td className="px-6 py-4 whitespace-nowrap">{task.priority}</td>
                                            <td className="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <Link href={route('tasks.edit', task.id)} className="text-indigo-600 hover:text-indigo-900 mr-4">Edit</Link>
                                                <button onClick={() => deleteTask(task.id)} disabled={processing} className="text-red-600 hover:text-red-900">
                                                    Delete
                                                </button>
                                            </td>
                                        </tr>
                                    ))}
                                    {tasks.data.length === 0 && (
                                        <tr>
                                            <td className="px-6 py-4 whitespace-nowrap text-center" colSpan="5">No tasks found.</td>
                                        </tr>
                                    )}
                                </tbody>
                            </table>
                             <Pagination links={tasks.links} />
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}