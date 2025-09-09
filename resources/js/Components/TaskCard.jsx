import { Link, useForm } from '@inertiajs/react';

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

export default function TaskCard({ task, onDelete }) {
    const { processing } = useForm();
    const progress = task.progress_percentage;

    return (
        <div className={`bg-white overflow-hidden shadow-sm rounded-lg p-4 flex flex-col justify-between ${priorityClass(task.priority)}`}>
            <div>
                <div className="flex justify-between items-start">
                    <h3 className="text-lg font-bold text-gray-900">{task.title}</h3>
                    <span className="text-sm font-semibold bg-gray-200 text-gray-700 px-2 py-1 rounded-full">{task.category?.title || 'N/A'}</span>
                </div>
                <p className="text-sm text-gray-600 mt-2 min-h-[40px]">{task.description}</p>
            </div>

            {/* Progress Bar Section */}
            <div className="mt-4">
                <div className="flex justify-between items-center text-xs text-gray-500 mb-1">
                    <span>Progress</span>
                    <span>{task.completed_subtasks_count} / {task.subtasks_count}</span>
                </div>
                <div className="w-full bg-gray-200 rounded-full h-2.5">
                    <div className="bg-blue-600 h-2.5 rounded-full" style={{ width: `${progress}%` }}></div>
                </div>
            </div>

            <div className="mt-4 flex justify-between items-center">
                <div className="flex items-center space-x-2">
                    <span className={`px-2 py-1 text-xs font-semibold rounded-full ${task.status === 'Completed' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800'}`}>{task.status}</span>
                    {task.due_date && (
                        <span className="text-xs text-gray-500">
                            Due: {new Date(task.due_date).toLocaleDateString()}
                        </span>
                    )}
                </div>
                <div className="flex items-center space-x-2">
                    <Link href={route('tasks.show', task.id)} className="text-sm text-gray-600 hover:text-gray-900 font-medium">View</Link>
                    <Link href={route('tasks.edit', task.id)} className="text-sm text-indigo-600 hover:text-indigo-900 font-medium">Edit</Link>
                    <button onClick={() => onDelete(task.id)} disabled={processing} className="text-sm text-red-600 hover:text-red-900 font-medium">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    );
};
