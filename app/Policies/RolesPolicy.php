<?php

namespace App\Policies;

use App\User;
use App\Role;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolesPolicy extends ResourcesPolicy
{
    use HandlesAuthorization;

    protected $resourceName = 'roles';

    /**
     * @param User $user
     * @param Role $role
     * @return bool
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */

    public function update(User $user, Role $role)
    {
        if (!$user->hasPermission('update-roles')) {
            $this->deny('update role');
        } elseif($role->id < 3 && !Auth::user()->hasRole('superadministrator')) {
            $this->deny('update this role');
        }
        return true;
    }
}
