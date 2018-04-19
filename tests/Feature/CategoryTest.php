<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CategoryTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function authorized_user_can_perform_CRUD()
    {
        $this->seed('LaratrustSeeder');

        $this->signIn()->followingRedirects()
            ->get(route('categories.index'))
            ->assertStatus(200)
            ->assertSee('do not have permission to');

        $this->signIn(null, [], ['read-category'])
            ->get(route('categories.index'))
            ->assertStatus(200)
            ->assertSee('Categories');

        $this->signIn(null, ['administrator'], ['read-category', 'create-category'])->followingRedirects()
            ->post(route('categories.store'), make('App\Category', ['name' => 'testCategory']))
            ->assertStatus(200)
            ->assertSee('category has been saved')
            ->assertSee('testCategory');

        $this->signIn(null, [], ['read-category', 'update-category'])->followingRedirects()
            ->put(route('categories.update', 1), make('App\Category', ['name' => 'changedName']))
            ->assertStatus(200)
            ->assertSee('changedName')
            ->assertSee('category has been updated');

        $this->signIn(null, [], ['read-category', 'delete-category'])->followingRedirects()
            ->delete(route('categories.destroy', 1))
            ->assertStatus(200)
            ->assertSee('category has been deleted');
    }
}

