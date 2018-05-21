<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class PermissionsPolicy extends ResourcesPolicy
{
    use HandlesAuthorization;

    protected $resourceName = 'permission';

}
