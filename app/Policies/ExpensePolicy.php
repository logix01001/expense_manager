<?php

namespace App\Policies;

use App\User;
use Spatie\Permission\Models\Role;
use Illuminate\Auth\Access\HandlesAuthorization;

class ExpensePolicy
{
    use HandlesAuthorization;


    public function create(User $user)
    {

        $role = Role::find($user->role_id_fk);
        if ($role->hasPermissionTo('expense-create')) {
            return true;
        }

        return false;
    }

    public function update(User $user)
    {
       $role = Role::find($user->role_id_fk);

        if ( $role->hasPermissionTo('expense-update')) {
            return true;
        }

        return false;
    }

    public function delete(User $user)
    {
        $role = Role::find( $user->role_id_fk);
        if ($role->hasPermissionTo('expense-delete')) {
            return true;
        }

        return false;
    }

    public function restore(User $user, Role $role)
    {
        //
    }

    public function forceDelete(User $user, Role $role)
    {
        //
    }
}
