<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * @param null $user
     * @param array $roles
     * @param array $permissions
     * @return $this
     */
    protected function signIn($user = null, $roles = [], $permissions = [])
    {
        $user = $user ?: create('App\User');

        $user->attachRoles($roles)->attachPermissions($permissions);

        $this->actingAs($user);

        return $this;
    }
}
