<?php

namespace Tests\Feature;

use App\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


class PostsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_read_blog()
    {
        $this->get(route('blog.index'))
            ->assertStatus(200)
            ->assertSee('All posts');
    }

    /** @test */
    public function a_user_can_see_a_posts()
    {
        $category = create('App\Category');
        $user = create('App\User');
        $post = create('App\Post', ['status' => '3']);

        $this->get(route('blog.index'))
            ->assertSee($post->title)
            ->assertSee($category->name)
            ->assertSee($user->name);

        $this->get(route('blog.show', $post->slug))
            ->assertSee($post->title)
            ->assertSee($post->body);
    }

    /** @test */
    public function a_user_cannot_see_unpublished_posts()
    {
        create('App\Category');
        create('App\User');
        $draft = create('App\Post', ['status' => '1']);
        $unpublished = create('App\Post', ['status' => '2']);

        $this->get(route('blog.show', $draft->slug))
            ->assertDontSee($draft->title);

        $this->get(route('blog.show', $unpublished->slug))
            ->assertDontSee($unpublished->title);

        $this->get(route('blog.index'))
            ->assertDontSee($draft->title)
            ->assertDontSee($unpublished->title);
    }

    /** @test */
    public function unauthenticated_user_cannot_create_post()
    {
        $this->get(route('posts.create'))
            ->assertRedirect(route('login'));

        $this->post(route('posts.store'), make('App\Post'))
            ->assertRedirect(route('login'));

        $this->assertTrue(count(Post::all()) == 0);
    }

    /** @test */
    public function unauthorized_user_cannot_create_post()
    {

        create('App\User');
        create('App\Category');
        $this->seed('LaratrustSeeder');
        $this->signIn()->followingRedirects()
            ->get(route('posts.create'))
            ->assertStatus(200)
            ->assertSee('do not have permission')
            ->assertViewIs('blog.index');
        $this->signIn(null,[], ['read-post'])->followingRedirects()
            ->post(route('posts.store'), make('App\Post', ['_token' => csrf_token()]))
            ->assertStatus(200)
            ->assertSee('do not have permission')
            ->assertViewIs('blog.index');

        $this->assertTrue(count(Post::all()) == 0);

    }

    /** @test */
    public function unauthorized_user_cannot_update_post()
    {
        create('App\User');
        create('App\Category');
        create('App\Post', ['title' => 'testing']);
        $this->signIn()->followingRedirects()
            ->get(route('posts.edit', 1))
            ->assertSee('do not have permission to edit')
            ->assertViewIs('blog.index');
        $this->signIn()->followingRedirects()
            ->put(route('posts.update', 1), make('App\Post', ['title' => 'changing']))
            ->assertStatus(200)
            ->assertSee('do not have permission to edit')
            ->assertViewIs('blog.index');

        $this->assertTrue(Post::query()->first()->title == 'testing');

    }

/** @test */
    public function unauthorized_user_cannot_delete_post()
    {
        create('App\User');
        create('App\Category');
        create('App\Post');

        $this->signIn()->followingRedirects()
            ->delete(route('posts.destroy', 1))
            ->assertStatus(200)
            ->assertSee('do not have permission to delete')
            ->assertViewIs('blog.index');

        $this->assertTrue(count(Post::all()) == 1);
    }

    /** @test */
    public function authorized_user_can_create_and_publish_posts()
    {
        $this->seed('LaratrustSeeder');

        $this->signIn(null, [], ['create-post'])
            ->get(route('posts.create'))
            ->assertStatus(200)
            ->assertViewIs('manage.posts.create')
            ->assertSee('Save Draft')
            ->assertDontSee('Publish');

        create('App\Category');

        $post = make('App\Post', ['user_id' => 8, 'submit_type' => 'Publish']);
        $this->signIn(null, [], ['create-post', 'read-post'])->followingRedirects()
            ->post(route('posts.store'), $post)
            ->assertStatus(200)
            ->assertViewIs('manage.posts.index')
            ->assertSee('Published (0)')
            ->assertSee($post['title'])
            ->assertSee('Draft has been saved!');

        $this->assertTrue(count(Post::all()) == 1);
        $this->assertTrue(Post::query()->first()->status == 1);

        $this->signIn(null, [], ['create-post', 'publish-post'])->followingRedirects()
            ->get(route('posts.create'))
            ->assertStatus(200)
            ->assertSee('Publish');

        $post = make('App\Post', ['user_id' => 9, 'submit_type' => 'Publish']);
        $this->signIn(null, [], ['publish-post', 'create-post', 'read-post'])->followingRedirects()
            ->post(route('posts.store'), $post)
            ->assertStatus(200)
            ->assertSee('Published (1)')
            ->assertSee($post['title'])
            ->assertSee('has been published!');

        $this->assertTrue(count(Post::all()) == 2);
        $this->assertTrue(Post::query()->where('id', 2)->first()->status == 3);

    }

    /** @test */
    public function authorized_user_can_update_posts()
    {
        $this->seed('LaratrustSeeder');

        create('App\Category');
        $user = create('App\User');

        $user->attachPermissions(['create-post', 'read-post', 'update-post']);

        $this->actingAs($user)->followingRedirects()
            ->post(route('posts.store'), make('App\Post', ['title' => 'firstTitle']))
            ->assertStatus(200)
            ->assertSee('has been saved');

        $this->actingAs($user)->followingRedirects()
            ->put(route('posts.update', 1), make('App\Post', ['title' => 'secondTitle']))
            ->assertStatus(200)
            ->assertSee('has been saved');

        $this->assertTrue(Post::query()->first()->title == 'secondTitle');

        $this->signIn(null,[], ['read-post', 'publish-post'])->followingRedirects()
            ->put(route('posts.update', 1), make('App\Post', ['title' => 'thirdTitle']))
            ->assertStatus(200)
            ->assertSee('has been saved');

        $this->assertTrue(Post::query()->first()->title == 'thirdTitle');
    }

    /** @test */
    public function authorized_user_can_delete_posts()
    {
        $this->seed('LaratrustSeeder');

        create('App\Category');
        $user = create('App\User');

        $user->attachPermissions(['create-post', 'read-post', 'update-post', 'delete-post']);

        $this->actingAs($user)
            ->post(route('posts.store'), make('App\Post'));

        $this->assertTrue(count(Post::all()) == 1);

        $this->actingAs($user)->followingRedirects()
            ->delete(route('posts.destroy', 1))
            ->assertStatus(200)
            ->assertViewIs('manage.posts.index')
            ->assertSee('has been deleted');

        $this->assertTrue(count(Post::all()) == 0);

        create('App\Post');
        $this->signIn(null,[], ['read-post', 'publish-post'])->followingRedirects()
            ->delete(route('posts.destroy', 2))
            ->assertStatus(200)
            ->assertViewIs('manage.posts.index')
            ->assertSee('has been deleted');
    }


    /** @test */
    public function post_requires_necessary_fields()
    {
        $this->seed('LaratrustSeeder');

        create('App\Category');
        $this->signIn(null, [], ['create-post', 'read-post'])
            ->post(route('posts.store'), make('App\Post', ['title' => '']))
            ->assertSessionHasErrors('title');
        $this->signIn(null, [], ['create-post', 'read-post'])
            ->post(route('posts.store'), make('App\Post', ['slug' => '']))
            ->assertSessionHasErrors('slug');
        $this->signIn(null, [], ['create-post', 'read-post'])
            ->post(route('posts.store'), make('App\Post', ['body' => '']))
            ->assertSessionHasErrors('body');

        $user = create('App\User')->attachPermissions(['create-post', 'read-post', 'update-post']);

        $this->actingAs($user)->post(route('posts.store'), make('App\Post'));

        $this->actingAs($user)->put(route('posts.update', 1), make('App\Post', ['title' => '']))
            ->assertSessionHasErrors('title');

        $this->actingAs($user)->put(route('posts.update', 1), make('App\Post', ['body' => '']))
            ->assertSessionHasErrors('body');
    }
}
