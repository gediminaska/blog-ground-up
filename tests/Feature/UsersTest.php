<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UsersTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function unauthorized_user_cannot_access_any_page()
    {
        $this->seed('LaratrustSeeder');

        $this->signIn()
            ->get(route('users.index'))
            ->assertRedirect(route('blog.index'));
        $this->signIn()
            ->get(route('users.create'))
            ->assertRedirect(route('blog.index'));
        $this->signIn()
            ->get(route('users.show', 1))
            ->assertRedirect(route('blog.index'));
        $this->signIn()
            ->get(route('users.edit', 1))
            ->assertRedirect(route('blog.index'));
        $this->signIn()
            ->put(route('users.update', 1), make('App\User'))
            ->assertRedirect(route('blog.index'));
        $this->signIn()
            ->post(route('users.store'), make('App\User'))
            ->assertRedirect(route('blog.index'));
    }

    /** @test */
    public function authorized_user_can_perform_CRU()
    {
        $this->seed('LaratrustSeeder');

        $this->signIn(null,[],['read-users'])
            ->get(route('users.index'))
            ->assertStatus(200);
        $this->signIn(null,[],['read-users'])->followingRedirects()
            ->get(route('users.show', 1))
            ->assertStatus(200);

        $this->signIn(null,[],['create-users'])
            ->get(route('users.create'))
            ->assertStatus(200);
        $this->signIn(null,[],['read-users','create-users'])->followingRedirects()
            ->post(route('users.store'), make('App\User', ['password_options' => 'auto']))
            ->assertStatus(200)
            ->assertViewIs('manage.users.create')
            ->assertSee('saved successfully');

        $this->signIn(null,[],['update-users'])
            ->get(route('users.edit', 3))
            ->assertStatus(200);
        $this->signIn(null,[],['read-users','update-users'])->followingRedirects()
            ->put(route('users.update', 3), make('App\User', ['password_options' => 'keep']))
            ->assertStatus(200)
            ->assertViewIs('manage.users.show')
            ->assertSee('Changes successfully saved');
    }

}

