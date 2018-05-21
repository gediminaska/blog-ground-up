<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class UsersPolicy extends ResourcesPolicy
{
    use HandlesAuthorization;

    protected $resourceName = 'users';
}
