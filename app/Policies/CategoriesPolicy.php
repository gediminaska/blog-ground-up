<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class CategoriesPolicy extends ResourcesPolicy
{
    use HandlesAuthorization;

    protected $resourceName = 'category';

}
