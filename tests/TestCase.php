<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function signIn($user = null, $roles = [], $permissions = [])
    {
        $user = $user ?: create('App\User');

        foreach ($roles as $role) {
            $user->attachRole($role);
        }

        foreach ($permissions as $permission) {
            $user->attachPermission($permission);
        }

        $this->actingAs($user);

        return $this;
    }
}
