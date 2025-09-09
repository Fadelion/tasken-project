import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, Link, router } from "@inertiajs/react";
import { useState } from 'react';
import Modal from '@/Components/Modal';
import SecondaryButton from '@/Components/SecondaryButton';
import DangerButton from '@/Components/DangerButton';

export default function Index({ auth, categories }) {
    const [confirmingCategoryDeletion, setConfirmingCategoryDeletion] = useState(false);
    const [categoryToDelete, setCategoryToDelete] = useState(null);

    const confirmCategoryDeletion = (category) => {
        setCategoryToDelete(category);
        setConfirmingCategoryDeletion(true);
    };

    const deleteCategory = (e) => {
        e.preventDefault();
        if (categoryToDelete) {
            router.delete(route('categories.destroy', categoryToDelete.id), {
                onSuccess: () => closeModal(),
            });
        }
    };

    const closeModal = () => {
        setConfirmingCategoryDeletion(false);
        setCategoryToDelete(null);
    };

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Catégories</h2>}
        >
            <Head title="Catégories" />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6 text-gray-900">
                            <Link href={route('categories.create')} className="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4 inline-block">
                                Ajouter une catégorie
                            </Link>
                            <table className="min-w-full divide-y divide-gray-200">
                                <thead className="bg-gray-50">
                                    <tr>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Titre</th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody className="bg-white divide-y divide-gray-200">
                                    {categories.data.map(category => (
                                        <tr key={category.id}>
                                            <td className="px-6 py-4 whitespace-nowrap">{category.title}</td>
                                            <td className="px-6 py-4 whitespace-nowrap">
                                                <Link href={route('categories.edit', category.id)} className="text-indigo-600 hover:text-indigo-900 mr-4">
                                                    Modifier
                                                </Link>
                                                <button onClick={() => confirmCategoryDeletion(category)} className="text-red-600 hover:text-red-900">
                                                    Supprimer
                                                </button>
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <Modal show={confirmingCategoryDeletion} onClose={closeModal}>
                <form onSubmit={deleteCategory} className="p-6">
                    <h2 className="text-lg font-medium text-gray-900">
                        Êtes-vous sûr de vouloir supprimer cette catégorie ?
                    </h2>
                    <p className="mt-1 text-sm text-gray-600">
                        Une fois la catégorie supprimée, toutes ses ressources et données seront définitivement perdues.
                    </p>
                    <div className="mt-6 flex justify-end">
                        <SecondaryButton onClick={closeModal}>Annuler</SecondaryButton>
                        <DangerButton className="ml-3">
                            Supprimer la catégorie
                        </DangerButton>
                    </div>
                </form>
            </Modal>
        </AuthenticatedLayout>
    );
}