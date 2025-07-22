<?php

namespace App\Providers;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{

    protected $policies = [
        User::class => \App\Policies\UserPolicy::class,
        Role::class => \App\Policies\RolePolicy::class,
        Permission::class => \App\Policies\PermissionPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();

        // Optional: Custom gate definitions
        Gate::before(function ($user, $ability) {
            if ($user->hasRole('Admin')) {
                return true;
            }
        });
    }
}