<?php

namespace App\Policies;

use App\User;
use App\Role;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolesPolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @return bool
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(User $user)
    {
        if (!$user->hasPermission('read-roles')) {
            $this->deny('read role');
        }
        return true;
    }

    /**
     * @param User $user
     * @return bool
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(User $user)
    {
        if (!$user->hasPermission('read-roles')) {
            $this->deny('read role');
        }
        return true;
    }

    /**
     * @param User $user
     * @return bool
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create(User $user)
    {
        if (!$user->hasPermission('create-roles')) {
            $this->deny('create role');
        }
        return true;
    }

    /**
     * @param User $user
     * @param Role $role
     * @return bool
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */

    public function update(User $user, $role)
    {
        if (!$user->hasPermission('update-roles')) {
            $this->deny('update role');
        } elseif($role->id < 3 && !Auth::user()->hasRole('superadministrator')) {
            $this->deny('update this role');
        }
        return true;
    }
}
