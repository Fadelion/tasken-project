<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Affiche le tableau de bord admin avec les statistiques globales.
     */
    public function index() 
    {
        // Récupère les statistiques de l'application
        $stats = [
            'totalUsers' => User::count(),
            'totalTasks' => Task::count(),
            'totalCategories' => Category::count(),
        ];

        return Inertia::render('Admin/Dashboard', [
            'stats' => $stats,
        ]);
    }
}
