<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SubtaskController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

// Routes publiques
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// Routes nécessitant une authentification
Route::get('/dashboard', function () {
    $user = Auth::user();
    $stats = [
        'tasks_in_progress' => $user->tasks()->where('status', 'In Progress')->count(),
        'tasks_completed' => $user->tasks()->where('status', 'Completed')->count(),
        'high_priority_tasks' => $user->tasks()->where('priority', 'High')->count(),
    ];

    return Inertia::render('Dashboard', ['stats' => $stats]);
})->middleware(['auth', 'verified'])->name('dashboard');

// Gestion de profil
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // route des taches et catégories
    Route::resource('tasks', TaskController::class);
    Route::resource('categories', CategoryController::class);

    // Nested routes pour les sous-tâches
    Route::resource('tasks.subtasks', SubtaskController::class)->shallow()->only(['store', 'destroy', 'update']);
});

// Routes pour l'administration (protégées par le middleware 'isAdmin' dont l'alias est admin)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::patch('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');
});

require __DIR__ . '/auth.php';
