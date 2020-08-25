<?php

namespace App\Policies;

use Spatie\Permission\Models\Role;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy
{
    use HandlesAuthorization;



    public function create(User $user)
    {

        $role = Role::find($user->role_id_fk);
        if ($role->hasPermissionTo('role-create')) {
            return true;
        }

        return false;
    }

    public function update(User $user)
    {
       $role = Role::find($user->role_id_fk);

        if ( $role->hasPermissionTo('role-update')) {
            return true;
        }

        return false;
    }

    public function delete(User $user)
    {
        $role = Role::find( $user->role_id_fk);
        if ($role->hasPermissionTo('role-delete')) {
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
