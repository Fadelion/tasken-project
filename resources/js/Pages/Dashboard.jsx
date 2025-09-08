import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';

// Stat Card Component
function StatCard({ title, value, colorClass }) {
    return (
        <div className={`bg-white overflow-hidden shadow-sm sm:rounded-lg ${colorClass}`}>
            <div className="p-6 text-gray-900">
                <h3 className="text-lg font-semibold text-gray-500">{title}</h3>
                <p className="mt-2 text-3xl font-bold">{value}</p>
            </div>
        </div>
    );
}

export default function Dashboard({ auth, stats }) {
    return (
        <AuthenticatedLayout
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>}
        >
            <Head title="Dashboard" />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <StatCard 
                            title="Tasks In Progress" 
                            value={stats.tasks_in_progress} 
                            colorClass="border-l-4 border-blue-500"
                        />
                        <StatCard 
                            title="Tasks Completed" 
                            value={stats.tasks_completed} 
                            colorClass="border-l-4 border-green-500"
                        />
                        <StatCard 
                            title="High Priority Tasks" 
                            value={stats.high_priority_tasks} 
                            colorClass="border-l-4 border-red-500"
                        />
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
