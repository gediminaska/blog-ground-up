<?php

namespace Tests\Feature;

use App\Comment;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CommentTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function post_author_can_delete_comments()
    {
        create('App\User');
        create('App\Category');
        create('App\Post');
        create('App\Comment');

        $this->followingRedirects()
            ->delete(route('comments.delete'), ['comment_id' => [1]])
            ->assertStatus(200)
            ->assertSee('Comments deleted.');

        $this->assertTrue(count(Comment::all()) == 0);
    }
}

