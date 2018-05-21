<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class ResourcesPolicy
{
    use HandlesAuthorization;

    protected $methodPermissions = [
        'index' => 'read',
        'show' => 'read',
        'create' => 'create',
        'store' => 'create',
        'edit' => 'update',
        'update' => 'update',
        'destroy' => 'delete',
        ];

    protected $resourceName = '';

    /**
     * @param $name
     * @param $arguments
     * @return bool
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function __call($name, $arguments)
    {
        if (!$arguments[0]->hasPermission($this->methodPermissions[$name] . "-" . $this->resourceName)) {
            $this->deny($this->methodPermissions[$name] . " " . $this->resourceName);
        }
        return true;
    }
}
