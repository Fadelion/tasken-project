import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';

// Composant pour afficher une carte de statistique
const StatCard = ({ title, value, icon }) => (
    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div className="p-6 text-gray-900 flex items-center">
            <div className="bg-blue-500 text-white rounded-full p-3 mr-4">
                {icon}
            </div>
            <div>
                <div className="text-sm text-gray-500">{title}</div>
                <div className="text-2xl font-bold">{value}</div>
            </div>
        </div>
    </div>
);

// Icônes SVG pour les cartes
const UsersIcon = () => <svg xmlns="http://www.w3.org/2000/svg" className="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M15 21a6 6 0 00-9-5.197m0 0A5.978 5.978 0 0112 13a5.979 5.979 0 012.121.303m-2.121-1.903a4 4 0 10-5.292 0" /></svg>;
const TasksIcon = () => <svg xmlns="http://www.w3.org/2000/svg" className="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>;
const CategoriesIcon = () => <svg xmlns="http://www.w3.org/2000/svg" className="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2H5a2 2 0 00-2 2v2m14 0h-2M5 11h2" /></svg>;


export default function AdminDashboard({ auth, stats }) {
    return (
        <AuthenticatedLayout
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Tableau de Bord Admin</h2>}
        >
            <Head title="Admin Dashboard" />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                        {/* Carte pour le nombre total d'utilisateurs */}
                        <StatCard 
                            title="Utilisateurs" 
                            value={stats.totalUsers}
                            icon={<UsersIcon />} 
                        />
                        {/* Carte pour le nombre total de tâches */}
                        <StatCard 
                            title="Tâches" 
                            value={stats.totalTasks}
                            icon={<TasksIcon />} 
                        />
                        {/* Carte pour le nombre total de catégories */}
                        <StatCard 
                            title="Catégories" 
                            value={stats.totalCategories}
                            icon={<CategoriesIcon />}
                        />
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
