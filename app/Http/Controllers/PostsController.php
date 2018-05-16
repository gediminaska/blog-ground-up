<?php

namespace App\Http\Controllers;

use App\Category;
use App\Post;
use App\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Image;
use TheoryThree\LaraToaster\LaraToaster as Toaster;
use Cache;
use App\Image as PostImage;



class PostsController extends Controller
{

    /**
     * @return \Illuminate\Http\RedirectResponse|mixed
     */
    public function index()
    {

        if ($this->userCannot('read-post')) {
            return redirect()->route('blog.index');
        }
        Auth::user()->hasPermission('publish-post') ?
            $posts = Post::query()->orderBy('updated_at', 'desc')->get() :
            $posts = Post::query()->where('user_id', Auth::user()->id)->get();

        return $this->sortedByStatus($posts);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create()
    {
        if ($this->userCannot('create-post')) {
            return redirect()->route('blog.index');
        }
        $categories = Category::all();
        $tags = Tag::query()->orderBy('name', 'asc')->get();
        return view('manage.posts.create', compact('tags', 'categories'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {

        if ($this->userCannot('create-post')) {
            return redirect()->route('blog.index');
        }
        if ($request->submit_type == 'New tag') {
            return $this->saveTags($request);
        } elseif ($request->submit_type == 'Delete draft') {
            $toaster = new Toaster;
            $toaster->success("Draft deleted.");
            return redirect()->route('posts.index');
        }
        $this->validatePostData($request);
        $post = new Post;
        $request->request->add(['post_id' => $post->id]);
        $post = $this->setPostStatus($request, $post);
        $this->collectPostData($request, $post);
        $post->slug = $request->slug;
        $this->savePost($post, $request);
        Cache::forget('blog');
        return redirect()->route('posts.index');
    }


    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function show($id)
    {
        $post = Post::query()->find($id);

        if ($this->userCannot('read-post')) {
            return redirect()->route('blog.index');
        }
        return view('manage.posts.show', compact('post'));
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function edit($id)
    {

        $post = Post::query()->find($id)->first();
        $categories = Category::all();
        $tags = $this->collectTags();

        if ($this->userCannot('update-post')) {
            return redirect()->route('blog.index');
        } elseif (!$this->userIsAuthorOf($post) && $this->userCannot('publish-post')) {
            return redirect()->route('blog.index');
        }

        return view('manage.posts.edit', compact('post', 'categories', 'tags'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {

        $post = Post::query()->find($id)->first();

        if ($this->userCannot('update-post')) {
            return redirect()->route('blog.index');
        } elseif (!$this->userIsAuthorOf($post) && $this->userCannot('publish-post')) {
            return redirect()->route('blog.index');
        }
        if ($request->submit_type == 'New tag') {
            return $this->saveTags($request);
        } elseif ($request->submit_type == 'Delete draft') {
            return $this->destroy($post->id);
        } elseif ($request->submit_type == 'Delete selected images') {
            return $this->deleteSelectedImages($request, $post);
        } elseif ($request->input('slug') == $post->slug || !$request->input('slug')) {
            $this->validate($request, [
                'title' => 'required|min:3|max:60',
                'body' => 'required|min:5|max:4000',
                'category_id' => 'required|numeric',
            ]);
        } else {
            $this->validatePostData($request);
        }
        $request->user_id = $post->user_id;
        $this->collectPostData($request, $post)->setPostStatus($request, $post);
        $this->savePost($post, $request);
        Cache::forget('blog');

        return redirect()->route('posts.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $post = Post::query()->find($id);

        if ($this->userCannot('update-post')) {
            return redirect()->route('blog.index');
        } elseif (!$this->userIsAuthorOf($post) && $this->userCannot('publish-post')) {
            return redirect()->route('blog.index');
        }
        $post->tags()->detach();
        foreach ($post->comments as $comment) {$comment->delete();}
        $post->delete();

        $toaster = new Toaster;
        $toaster->success('The post ' . "'" . "$post->title" . "'" . ' has been deleted!');
        Cache::forget('blog');
        return redirect()->route('posts.index');
    }

    /**
     * @param $posts
     * @return mixed
     */
    private function sortedByStatus($posts)
    {
        $published = $posts->where('status', 3);
        $submitted = $posts->where('status', 2);
        $drafts = $posts->where('status', 1);
        return view('manage.posts.index', compact('published', 'submitted', 'drafts'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    private function saveTags(Request $request)
    {
        $this->validate($request, [
            'tagSlug' => 'required|min:3|max:20|unique:tags,name'
        ]);
        $tag = new Tag;
        $tag->name = $request->tagSlug;
        $tag->save();
        $toaster = new Toaster;
        $toaster->success("Tag was saved!");
        return redirect()->back();
    }

    /**
     * @param Request $request
     */
    private function validatePostData(Request $request): void
    {
        $this->validate($request, [
            'title' => 'required|min:3|max:60',
            'body' => 'required|min:5|max:4000',
            'slug' => 'required|min:3|unique:posts,slug',
            'category_id' => 'required|numeric',
            'user_id' => 'required|numeric',
        ]);
    }

    /**
     * @param Request $request
     * @param Post $post
     * @return $this
     */
    private function collectPostData(Request $request, Post $post)
    {
        $post->title = $request->title;
        $post->body = $request->body;
        if($request->slug && $request->slug != $post->slug) {
            $post->slug = $request->slug;
        }
        $post->category_id = $request->category_id;
        $post->user_id = $request->user_id;

        return $this;
    }

    /**
     * @param Request $request
     * @param Post $post
     * @return Post
     */
    private function setPostStatus(Request $request, Post $post)
    {
        if (($request->submit_type == 'Publish' || $request->submit_type == 'Publish again')  && Auth::user()->hasPermission('publish-post')) {
            $post->status = 3;
            $post->published_at = now();
            $toaster = new Toaster;
            $toaster->success("Post '" . $request->title . "'' has been published!");
        }
        elseif ($request->submit_type == 'Submit') {
            $post->status = 2;
            $toaster = new Toaster;
            $toaster->success("Post '" . $request->title . "'' has been submitted!");
        }
        else {
            $post->status = 1;
            $toaster = new Toaster;
            $toaster->success("Draft has been saved!");
        }
        return $post;
    }

    /**
     * @return array
     */
    private function collectTags(): array
    {
        $tags2 = Tag::query()->orderBy('name', 'asc')->get();
        $tags = array();
        foreach ($tags2 as $tag) {
            $tags[$tag->id] = $tag->name;
        }
        return $tags;
    }

    /**
     * @param Post $post
     * @return bool
     */
    private function userIsAuthorOf(Post $post): bool
    {
        return Auth::user()->id == $post->user->id;
    }

    /**
     * @param Post $post
     * @param Request $request
     */
    public function savePost(Post $post, Request $request)
    {
        $post->save();
        $post->tags()->sync($request->tags, false);
        if ($request->hasFile('images')) {
            $this->uploadImages($request, $post);
        }
    }

    /**
     * @param Request $request
     * @param Post $post
     */
    private function uploadImages(Request $request, Post $post)
    {
        foreach ($request->file('images') as $image) {
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $location = public_path('images/' . $filename);
            Image::make($image)->resize(800, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($location);

            $postImage = new PostImage;
            $postImage->name = $filename;
            $postImage->rank = 1;
            $postImage->post_id = $post->id;
            $postImage->save();
        }
    }

    /**
     * @param Request $request
     * @param $post
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteSelectedImages(Request $request, Post $post): \Illuminate\Http\RedirectResponse
    {
        foreach ($request->image_id as $image_id) {
            $image = $post->images()->where('id', $image_id);
            $image->delete();
        }
        $toaster = new Toaster;
        $toaster->success("Images deleted.");
        return redirect()->back();
    }

}
