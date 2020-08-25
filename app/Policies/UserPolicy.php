<?php

namespace App\Policies;

use App\User;
use Spatie\Permission\Models\Role;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function create(User $user)
    {

        $role = Role::find($user->role_id_fk);
        if ($role->hasPermissionTo('user-create')) {
            return true;
        }

        return false;
    }

    public function update(User $user)
    {
       $role = Role::find($user->role_id_fk);

        if ( $role->hasPermissionTo('user-update')) {
            return true;
        }

        return false;
    }

    public function delete(User $user)
    {
        $role = Role::find( $user->role_id_fk);
        if ($role->hasPermissionTo('user-delete')) {
            return true;
        }

        return false;
    }
}
