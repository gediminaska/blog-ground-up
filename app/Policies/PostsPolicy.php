<?php

namespace App\Policies;

use App\Post;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PostsPolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @param Post $post
     * @return bool
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(User $user)
    {
        if (!$user->hasPermission('read-post')) {
            $this->deny('read post');
        }
        return true;
    }
    /**
     * @param User $user
     * @param Post $post
     * @return bool
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(User $user, Post $post)
    {
        if (!$user->hasPermission('read-post')) {
            $this->deny('read post');
        } elseif (!$post->authorIsCurrentUser() && !$user->hasPermission('publish-post')) {
            $this->deny('publish post');
        }
        return true;
    }

    /**
     * @param User $user
     * @return bool
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create(User $user)
    {
        if (!$user->hasPermission('create-post')) {
            $this->deny('create post');
        }
        return true;
    }
    /**
     * @param User $user
     * @param Post $post
     * @return bool
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(User $user, Post $post)
    {
        if (!$user->hasPermission('update-post')) {
            $this->deny('update post');
        } elseif (!$post->authorIsCurrentUser() && !$user->hasPermission('publish-post')) {
            $this->deny('publish post');
        }
        return true;
    }
}
