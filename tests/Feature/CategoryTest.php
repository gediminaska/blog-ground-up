<?php

namespace Tests\Feature;

use App\Category;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Cache;

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
            ->assertViewIs('blog.index')
            ->assertSee('do not have permission to');

        $this->signIn()->followingRedirects()
            ->post(route('categories.store'), make('App\Category'))
            ->assertStatus(200)
            ->assertViewIs('blog.index')
            ->assertSee('do not have permission to');

        $this->assertTrue(count(Category::all()) == 0);

        create('App\Category', ['name'=>'beforetest']);
        $this->signIn(null,[],['read-category'])->followingRedirects()
            ->put(route('categories.update', 1), make('App\Category', ['name' => 'tests']))
            ->assertStatus(200)
            ->assertViewIs('blog.index')
            ->assertSee('do not have permission to');

        $this->signIn()->followingRedirects()
            ->get(route('categories.index'))
            ->assertStatus(200)
            ->assertViewIs('blog.index')
            ->assertSee('do not have permission to');

        $this->signIn(null, [], ['read-category'])
            ->get(route('categories.index'))
            ->assertStatus(200)
            ->assertViewIs('manage.categories.index')
            ->assertSee('Categories');

        $this->signIn(null, [], ['read-category', 'create-category'])->followingRedirects()
            ->post(route('categories.store'), make('App\Category', ['name' => 'testCategory']))
            ->assertStatus(200)
            ->assertViewIs('manage.categories.index')
            ->assertSee('category has been saved')
            ->assertSee('testCategory');

        $this->assertTrue(count(Category::all()) == 2);

        $this->signIn(null, [], ['read-category', 'update-category'])->followingRedirects()
            ->put(route('categories.update', 1), make('App\Category', ['name' => 'changedName']))
            ->assertStatus(200)
            ->assertViewIs('manage.categories.index')
            ->assertSee('changedName')
            ->assertSee('category has been updated');

        $this->signIn(null, [], ['read-category', 'delete-category'])->followingRedirects()
            ->delete(route('categories.destroy', 2))
            ->assertStatus(200)
            ->assertViewIs('manage.categories.index')
            ->assertSee('category has been deleted');

        $this->assertTrue(count(Category::all()) == 1);
    }

    /** @test */
    public function category_is_shown_in_blog_index()
    {
        create('App\Category', ['name' => 'Vue_php']);
        $this->get(route('blog.index'))
            ->assertStatus(200)
            ->assertViewIs('blog.index')
            ->assertSee('Vue_php');
    }

    /** @test */
    public function post_category_is_displayed()
    {
        create('App\Category', ['name' => 'Vue_php']);
        create('App\User');
        create('App\Post', ['category_id' => 1, 'slug' => 'vue']);
        $this->get(route('blog.show', 'vue'))
            ->assertStatus(200)
            ->assertViewIs('blog.single')
            ->assertSee('Vue_php');
    }

    /** @test */
    public function post_appears_in_category_page()
    {
        create('App\Category');
        create('App\Category');
        create('App\User');
        create('App\Post', ['title' => 'Kimono shorts']);
        $this->get(route('blog.category', 1))
            ->assertStatus(200)
            ->assertViewIs('blog.index')
            ->assertSee('Kimono shorts');
        $this->get(route('blog.category', 2))
            ->assertStatus(200)
            ->assertViewIs('blog.index')
            ->assertDontSee('Kimono shorts');

        Cache::forget('categories');
    }
}

