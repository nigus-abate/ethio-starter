<?php

namespace App\Policies;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->can('view roles');
    }

    public function view(User $user, Role $role)
    {
        return $user->can('view roles');
    }

    public function create(User $user)
    {
        return $user->can('create roles');
    }

    public function update(User $user, Role $role)
    {
        // Prevent editing of Super Admin role
        if ($role->name === 'Super Admin') {
            return false;
        }
        return $user->can('edit roles');
    }

    public function delete(User $user, Role $role)
    {
        // Prevent deletion of Super Admin role
        if ($role->name === 'Super Admin') {
            return false;
        }
        return $user->can('delete roles');
    }

    public function restore(User $user, Role $role)
    {
        return $user->can('delete roles');
    }

    public function forceDelete(User $user, Role $role)
    {
        return $user->can('delete roles');
    }
}