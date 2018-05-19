<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PermissionsPolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @return bool
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(User $user)
    {
        if (!$user->hasPermission('read-permission')) {
            $this->deny('read permission');
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
        if (!$user->hasPermission('read-permission')) {
            $this->deny('read permission');
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
        if (!$user->hasPermission('create-permission')) {
            $this->deny('create permission');
        }
        return true;
    }

    /**
     * @param User $user
     * @return bool
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */

    public function update(User $user)
    {
        if (!$user->hasPermission('update-permission')) {
            $this->deny('update permission');
        }
        return true;
    }

}
