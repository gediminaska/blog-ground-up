<?php

namespace App\Policies;

use App\Post;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PostsPolicy extends ResourcesPolicy
{
    use HandlesAuthorization;

    protected $resourceName = 'post';

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
