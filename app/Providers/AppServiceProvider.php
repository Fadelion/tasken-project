<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);
        // Accorde tous les droits à l'administrateur.
        // Cette règle est vérifiée avant toutes les autres Policies.
        Gate::before(function (User $user, $ability) {
            if ($user->role === 'admin') {
                return true;
            }
        });
    }
}
