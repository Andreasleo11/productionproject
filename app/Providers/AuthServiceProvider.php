<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Define gate for Admin links
        Gate::define('view-admin-links', function ($user) {
            return $user->hasRoleAccess('Admin');
        });

        // Define gate for PE links
        Gate::define('view-pe-links', function ($user) {
            return $user->hasRoleAccess('PE');
        });

        // Define gate for Store links
        Gate::define('view-store-links', function ($user) {
            return $user->hasRoleAccess('Store');
        });

        // Define gate for PPIC links
        Gate::define('view-ppic-links', function ($user) {
            return $user->hasRoleAccess('PPIC');
        });

        // Define gate for Maintenance links
        Gate::define('view-maintenance-links', function($user) {
            return $user->hasRoleAccess('Maintenance');
        });

        // Define gate for Second links
        Gate::define('view-second-process-links', function($user){
            return $user->hasRoleAccess('SecondProcess');
        });

        Gate::define('view-assembly-process-links', function($user){
            return $user->hasRoleAccess('AssemblyProcess');
        });
    }
}
