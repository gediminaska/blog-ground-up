<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CategoriesPolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @return bool
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(User $user)
    {
        if (!$user->hasPermission('read-category')) {
            $this->deny('read category');
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
        if (!$user->hasPermission('read-category')) {
            $this->deny('read category');
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
        if (!$user->hasPermission('create-category')) {
            $this->deny('create category');
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
        if (!$user->hasPermission('update-category')) {
            $this->deny('update category');
        }
        return true;
    }
    /**
     * @param User $user
     * @return bool
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */

    public function destroy(User $user)
    {
        if (!$user->hasPermission('delete-category')) {
            $this->deny('delete category');
        }
        return true;
    }
}
