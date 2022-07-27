<?php

namespace App\Providers;

use App\Models\{Category, Product, Role, User};
use App\Policies\{CategoryPolicy, PermissionPolicy, ProductPolicy, RolePolicy, UserPolicy};
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Permission;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        User::class       => UserPolicy::class,
        Category::class   => CategoryPolicy::class,
        Product::class    => ProductPolicy::class,
        Role::class       => RolePolicy::class,
        Permission::class => PermissionPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        //
    }
}
