<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AuthTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function authorized_user_can_perform_CRU()
    {
        $this->seed('LaratrustSeeder');

        $this->signIn(null,[],['read-permission'])
            ->get(route('permissions.index'))
            ->assertStatus(200);
        $this->signIn(null,[],['read-permission'])
            ->get(route('permissions.show', 1))
            ->assertStatus(200);

        $this->signIn(null,[],['create-permission'])
            ->get(route('permissions.create'))
            ->assertStatus(200);
        $this->signIn(null,[],['read-permission','create-permission'])->followingRedirects()
            ->post(route('permissions.store'), make('App\Permission'))
            ->assertStatus(200)
            ->assertViewIs('manage.permissions.index')
            ->assertSee('successfully added');

        $this->signIn(null,[],['update-permission'])
            ->get(route('permissions.edit', 3))
            ->assertStatus(200);
        $this->signIn(null,[],['read-permission','update-permission'])->followingRedirects()
            ->put(route('permissions.update', 3), make('App\Permission'))
            ->assertStatus(200)
            ->assertViewIs('manage.permissions.show')
            ->assertSee('Updated the');
    }
}