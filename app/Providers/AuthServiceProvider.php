<?php

namespace App\Providers;

use App\User;
use App\Policies\RolePolicy;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;


class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        //'App\Model' => 'App\Policies\ModelPolicy',
        'Spatie\Permission\Models\Role' => 'App\Policies\RolePolicy',
        'Spatie\Permission\Models\Permission' => 'App\Policies\PermissionPolicy'
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */

    protected $gates = [

        'role.create' => 'App\Policies\RolePolicy@create',
        'role.update' => 'App\Policies\RolePolicy@update',
        'role.delete' => 'App\Policies\RolePolicy@delete',

        'user.create' => 'App\Policies\UserPolicy@create',
        'user.update' => 'App\Policies\UserPolicy@update',
        'user.delete' => 'App\Policies\UserPolicy@delete',

        'expensecategory.create' => 'App\Policies\ExpenseCategoryPolicy@create',
        'expensecategory.update' => 'App\Policies\ExpenseCategoryPolicy@update',
        'expensecategory.delete' => 'App\Policies\ExpenseCategoryPolicy@delete',

        'expense.create' => 'App\Policies\ExpensePolicy@create',
        'expense.update' => 'App\Policies\ExpensePolicy@update',
        'expense.delete' => 'App\Policies\ExpensePolicy@delete',


    ];
    public function boot()
    {
        $this->registerPolicies();

        Passport::routes();

        $this->registerGates();

        // Gate::define('role-update','App\Policies\RolePolicy@update',Auth()->user());
        // Gate::define('role-delete','App\Policies\RolePolicy@delete',Auth()->user());
        // Gate::define('role-create','App\Policies\RolePolicy@create',Auth()->user());

        //
    }

    /**
     * Register the application's gates.
     *
     * @return void
     */
    public function registerGates()
    {
        foreach ($this->gates as $key => $value) {
            Gate::define($key, $value);
        }
    }
}
