<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laracasts\Integrated\Extensions\Laravel as IntegrationTest;

class AuthTest extends TestCase
{
    use DatabaseMigrations;


    /** @test */
    public function a_user_can_see_register_page()
    {
        $this->get(route('register'))
            ->assertStatus(200)
            ->assertSee('Register');
    }

    /** @test */
    public function user_can_register()
    {
        $this->visit('/register')
            ->type('Meooiw', 'name')
            ->type('someone@outlook.com', 'email')
            ->type('secret', 'password')
            ->type('secret', 'password_confirmation')
            ->press('Register');

        $this->assertTrue(count(User::all()) == 1);
    }

//    /** @test */
//    public function authorized_user_can_perform_CRU()
//    {
//        $this->seed('LaratrustSeeder');
//
//        $this->signIn(null,[],['read-permission'])
//            ->get(route('permissions.index'))
//            ->assertStatus(200);
//        $this->signIn(null,[],['read-permission'])
//            ->get(route('permissions.show', 1))
//            ->assertStatus(200);
//
//        $this->signIn(null,[],['create-permission'])
//            ->get(route('permissions.create'))
//            ->assertStatus(200);
//        $this->signIn(null,[],['read-permission','create-permission'])->followingRedirects()
//            ->post(route('permissions.store'), make('App\Permission'))
//            ->assertStatus(200)
//            ->assertViewIs('manage.permissions.index')
//            ->assertSee('successfully added');
//
//        $this->signIn(null,[],['update-permission'])
//            ->get(route('permissions.edit', 3))
//            ->assertStatus(200);
//        $this->signIn(null,[],['read-permission','update-permission'])->followingRedirects()
//            ->put(route('permissions.update', 3), make('App\Permission'))
//            ->assertStatus(200)
//            ->assertViewIs('manage.permissions.show')
//            ->assertSee('Updated the');
//    }

}

