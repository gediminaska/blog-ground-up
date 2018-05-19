<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class RolesTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function unauthorized_user_cannot_access_any_page()
    {
        $this->seed('LaratrustSeeder');

        $this->signIn()
            ->get(route('roles.index'))
            ->assertRedirect(route('blog.index'));
        $this->signIn()
            ->get(route('roles.create'))
            ->assertRedirect(route('blog.index'));
        $this->signIn()
            ->get(route('roles.show', 1))
            ->assertRedirect(route('blog.index'));
        $this->signIn()
            ->get(route('roles.edit', 1))
            ->assertRedirect(route('blog.index'));
        $this->signIn()
            ->put(route('roles.update', 1), make('App\Role'))
            ->assertRedirect(route('blog.index'));
        $this->signIn()
            ->post(route('roles.store'), make('App\Role'))
            ->assertRedirect(route('blog.index'));
    }

    /** @test */
    public function authorized_user_can_perform_CRU()
    {
        $this->seed('LaratrustSeeder');

        $this->signIn(null,[],['read-roles'])
            ->get(route('roles.index'))
            ->assertStatus(200);
        $this->signIn(null,[],['read-roles'])
            ->get(route('roles.show', 1))
            ->assertStatus(200);

        $this->signIn(null,[],['create-roles'])
            ->get(route('roles.create'))
            ->assertStatus(200);
        $this->signIn(null,[],['read-roles','create-roles'])->followingRedirects()
            ->post(route('roles.store'), make('App\Role'))
            ->assertStatus(200)
            ->assertViewIs('manage.roles.index')
            ->assertSee('Successfully created');
        $this->signIn(null,[],['update-roles'])
            ->get(route('roles.edit', 3))
            ->assertStatus(200);
        $this->signIn(null,[],['read-roles','update-roles'])->followingRedirects()
            ->put(route('roles.update', 3), make('App\Role'))
            ->assertStatus(200)
            ->assertViewIs('manage.roles.show')
            ->assertSee('Successfully updated');
    }

}

