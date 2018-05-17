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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $this->authorize('index', [new Post, Auth::user()]);

        Auth::user()->hasPermission('publish-post') ?
            $posts = Post::query()->orderBy('updated_at', 'desc')->get() :
            $posts = Post::query()->where('user_id', Auth::user()->id)->get();

        return $this->viewSortedByStatus($posts);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', [Post::class, Auth::user()]);

        $categories = Category::all();
        $tags = Tag::query()->orderBy('name', 'asc')->get();
        return view('manage.posts.create', compact('tags', 'categories'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Request $request)
    {
        $post = new Post;
        $this->authorize('create', [$post, Auth::user()]);

        if($this->submitIsSecondary($request)) {
            return $this->processSecondarySubmits($request, $post);
        }

        $this->validatePostData($request, $post)
            ->collectPostData($request, $post)
            ->setPostStatus($request, $post)
            ->savePost($post, $request);

        Cache::forget('blog');

        return redirect()->route('posts.index');
    }


    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show($id)
    {
        $post = Post::query()->find($id);

        $this->authorize('show', [$post, Auth::user()]);

        return view('manage.posts.show', compact('post'));
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit($id)
    {
        $post = Post::query()->find($id);

        $this->authorize('update', [$post, Auth::user()]);

        $categories = Category::all();
        $tags = $this->collectTags();

        return view('manage.posts.edit', compact('post', 'categories', 'tags'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, $id)
    {
        $post = Post::query()->find($id);

        $this->authorize('update', [$post, Auth::user()]);

        if($this->submitIsSecondary($request)) {
            return $this->processSecondarySubmits($request, $post);
        }

        $this->validatePostData($request, $post)
            ->collectPostData($request, $post)
            ->setPostStatus($request, $post)
            ->savePost($post, $request);

        Cache::forget('blog');

        return redirect()->route('posts.index');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy($id)
    {
        $post = Post::query()->find($id);

        $this->authorize('update', [$post, Auth::user()]);

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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    private function viewSortedByStatus($posts)
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
     * @param $post
     * @return $this
     */

    private function validatePostData(Request $request, $post)
    {
        $this->validate($request, [
            'title' => 'required|min:3|max:60',
            'body' => 'required|min:5|max:4000',
            'category_id' => 'required|numeric',
            'user_id' => 'required|numeric',
        ]);

        if($request->input('slug') && $request->input('slug') ==! $post->slug || $request->input('slug') == '') {
            $this->validate($request, [
                'slug' => 'required|min:3|unique:posts,slug',
            ]);
        }
        return $this;
    }

    /**
     * @param Request $request
     * @param $post
     * @return $this
     */
    private function collectPostData(Request $request, $post)
    {
        $post->title = $request->title;
        $post->body = $request->body;
        if($request->slug && $request->slug != $post->slug) {
            $post->slug = $request->slug;
        }
        $post->category_id = $request->category_id;
        $post->user_id = $request->user_id ?: $post->user_id;

        return $this;
    }

    /**
     * @param Request $request
     * @param $post
     * @return mixed
     */
    private function setPostStatus(Request $request, $post)
    {
        if (($request->submit_type == 'Publish' || $request->submit_type == 'Publish again')  && Auth::user()->hasPermission('publish-post')) {
            $post->status = 3;
            $post->published_at = now();
            $toaster = new Toaster;
            $toaster->success("Post '" . $request->title . "'' has been published!");
        }
        elseif ($request->submit_type == 'Submit' || $request->submit_type == 'Submit again') {
            $post->status = 2;
            $toaster = new Toaster;
            $toaster->success("Post '" . $request->title . "'' has been submitted!");
        }
        else {
            $post->status = 1;
            $toaster = new Toaster;
            $toaster->success("Draft has been saved!");
        }
        return $this;
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
     * @param $post
     * @param Request $request
     */
    public function savePost($post, Request $request)
    {
        $post->save();
        $post->tags()->sync($request->tags, false);
        if ($request->hasFile('images')) {
            $this->uploadImages($request, $post);
        }
    }


    /**
     * @param Request $request
     * @param $post
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function processSecondarySubmits(Request $request, $post)
    {
        if ($request->submit_type == 'New tag') {
            return $this->saveTags($request);
        } elseif ($request->submit_type == 'Delete draft') {
            return $this->destroy($post->id);
        } elseif ($request->submit_type == 'Delete selected images') {
            return $this->deleteSelectedImages($request, $post);
        }
    }

    /**
     * @param Request $request
     * @param $post
     */
    private function uploadImages(Request $request, $post)
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
    public function deleteSelectedImages(Request $request, $post)
    {
        foreach ($request->image_id as $image_id) {
            $image = $post->images()->where('id', $image_id);
            $image->delete();
        }
        $toaster = new Toaster;
        $toaster->success("Images deleted.");
        return redirect()->back();
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function submitIsSecondary(Request $request): bool
    {
        return in_array($request->submit_type, ['New tag', 'Delete draft', 'Delete selected images']);
    }

}
