<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UsersPolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @return bool
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(User $user)
    {
        if (!$user->hasPermission('read-users')) {
            $this->deny('read user');
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
        if (!$user->hasPermission('read-users')) {
            $this->deny('read user');
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
        if (!$user->hasPermission('create-users')) {
            $this->deny('create user');
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
        if (!$user->hasPermission('update-users')) {
            $this->deny('update user');
        }
        return true;
    }
}
