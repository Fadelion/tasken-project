import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link, useForm, router } from '@inertiajs/react';
import TextInput from '@/Components/TextInput';
import Pagination from '@/Components/Pagination';
import TaskCard from '@/Components/TaskCard';
import { useState, useEffect } from 'react';
import { pickBy } from 'lodash';
import Modal from '@/Components/Modal';
import SecondaryButton from '@/Components/SecondaryButton';
import DangerButton from '@/Components/DangerButton';

export default function Index({ auth, tasks, filters, success }) {

    const { delete: destroy } = useForm();
    const [search, setSearch] = useState(filters.search || '');
    const [confirmingTaskDeletion, setConfirmingTaskDeletion] = useState(false);
    const [taskToDelete, setTaskToDelete] = useState(null);

    // Gestion de la recherche uniquement
    useEffect(() => {
        const timeout = setTimeout(() => {
            const query = pickBy({ search });
            router.get(route('tasks.index'), { ...query, page: 1 }, {
                preserveState: true,
                replace: true,
            });
        }, 300);
        return () => clearTimeout(timeout);
    }, [search]);

    // Gestion de la pagination : on conserve la recherche dans l'URL
    const handlePageChange = (url) => {
        const query = pickBy({ search });
        router.get(url, query, {
            preserveState: true,
            replace: true,
        });
    };

    const confirmTaskDeletion = (id) => {
        setTaskToDelete(id);
        setConfirmingTaskDeletion(true);
    };

    const deleteTask = (e) => {
        e.preventDefault();
        if (taskToDelete) {
            destroy(route('tasks.destroy', taskToDelete), {
                preserveScroll: true,
                onSuccess: () => closeModal(),
            });
        }
    };

    const closeModal = () => {
        setConfirmingTaskDeletion(false);
        setTaskToDelete(null);
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
                            className="block w-full md:w-1/3"
                            placeholder="Search tasks..."
                            onChange={(e) => setSearch(e.target.value)}
                        />
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        {tasks.data.map(task => (
                            <TaskCard key={task.id} task={task} onDelete={confirmTaskDeletion} />
                        ))}
                    </div>

                    {tasks.data.length === 0 && (
                        <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                            <p className="text-center text-gray-500">No tasks found.</p>
                        </div>
                    )}

                    <div className="mt-6">
                        <Pagination links={tasks.links} onPageChange={handlePageChange} />
                    </div>
                </div>
            </div>

            <Modal show={confirmingTaskDeletion} onClose={closeModal}>
                <form onSubmit={deleteTask} className="p-6">
                    <h2 className="text-lg font-medium text-gray-900">
                        Are you sure you want to delete this task?
                    </h2>
                    <p className="mt-1 text-sm text-gray-600">
                        Once the task is deleted, all of its data will be permanently deleted.
                    </p>
                    <div className="mt-6 flex justify-end">
                        <SecondaryButton onClick={closeModal}>Cancel</SecondaryButton>
                        <DangerButton className="ml-3">
                            Delete Task
                        </DangerButton>
                    </div>
                </form>
            </Modal>
        </AuthenticatedLayout>
    );
}
