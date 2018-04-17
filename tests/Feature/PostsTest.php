<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;


class PostsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_user_can_read_blog()
    {

        $response = $this->get(route('blog.index'));

        $response->assertStatus(200)->assertSee('All posts');
    }

    /** @test */
    public function a_user_can_see_a_posts()
    {
        $category = factory('App\Category')->create();
        $user = factory('App\User')->create();
        $post = factory('App\Post')->create(['status' => '3']);

        $this->get(route('blog.index'))
            ->assertSee($post->title, $category->name, $user->name);

        $this->get(route('blog.show', $post->slug))
            ->assertSee($post->title, $post->body);
    }
    /** @test */
    public function a_user_cannot_see_unpublished_posts()
    {
        factory('App\Category')->create();
        factory('App\User')->create();
        $draft = factory('App\Post')->create(['status' => '1']);
        $unpublished = factory('App\Post')->create(['status' => '2']);

        $this->get(route('blog.show', $draft->slug))
            ->assertDontSee($draft->title);

        $this->get(route('blog.show', $unpublished->slug))
            ->assertDontSee($unpublished->title);

       $this->get(route('blog.index'))
           ->assertDontSee($draft->title)
           ->assertDontSee($unpublished->title);
    }

    /** @test */
    public function a_user_can_see_register_page()
    {
        $this->get(route('register'))
            ->assertStatus(200);
    }

    /** @test */
    public function only_authenticated_and_authorized_user_can_create_post()
    {
        $this->followingRedirects()
            ->get(route('posts.create'))
            ->assertSee('login', 'Forgot password');

        $this->seed('LaratrustSeeder');

        $user = factory('App\User')->create();

        $this->actingAs($user)->followingRedirects()
            ->get(route('posts.create'))
            ->assertSee('do not have permission');

        $user->attachPermission('create-post');
        $this->actingAs($user)
            ->get(route('posts.create'))
            ->assertSee('Save Draft')
            ->assertDontSee('Publish');

        $user->attachPermission('publish-post');
        $this->actingAs($user)
            ->get(route('posts.create'))
            ->assertSee('Publish');
    }
}
