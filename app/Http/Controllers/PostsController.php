<?php

namespace App\Http\Controllers;

use App\Category;
use App\Post;
use App\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Image;
use Toaster;
use Cache;
use App\Image as PostImage;


class PostsController extends Controller
{


    public function __construct()
    {
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|mixed
     */
    public function index()
    {
        if (Auth::user()->hasPermission('publish-post')) {
            $posts = Post::with('comments', 'category')->orderBy('updated_at', 'desc')->get();
        } elseif (Auth::user()->hasPermission('read-post')) {
            $posts = Post::where('user_id', Auth::user()->id)->get();
        } else {
            return redirect()->back();
        }
        return $this->viewSorted($posts);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create()
    {
        $neededPermission = 'create-post';
        if (!Auth::user()->hasPermission($neededPermission)) {
            return $this->rejectUnauthorized($neededPermission);
        }
        $categories = Category::all();
        $tags = Tag::query()->orderBy('name', 'asc')->get();
        return view('manage.posts.create')->withCategories($categories)->withTags($tags);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $neededPermission = 'create-post';
        if (!Auth::user()->hasPermission($neededPermission)) {
            return $this->rejectUnauthorized($neededPermission);
        } elseif ($request->submit_type == 'New tag') {
            return $this->saveTags($request);
        } elseif ($request->submit_type == 'Delete draft') {
            Toaster::success("Draft deleted.");
            return redirect()->route('posts.index');
        }
        $this->validatePostData($request);
        $post = new Post;
        $post = $this->setPostStatus($request, $post);
        $this->collectPostData($request, $post);
        $post->slug = $request->slug;
        $post->save();
        $post->tags()->sync($request->tags, false);
        if ($request->hasFile('images')) {
            $this->uploadImages($request, $post);
        }
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

        $neededPermission = 'publish-post';
        if (!Auth::user()->hasPermission($neededPermission) && !$this->userIsAuthorOf($post)) {
            return $this->rejectUnauthorized($neededPermission);
        }
        return view('manage.posts.show')->withPost($post);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function edit($id)
    {
        $post = Post::query()->find($id);
        $neededPermission = 'update-post';
        if (!$this->userIsAuthorOf($post) && !Auth::user()->hasPermission('publish-post')) {
            return $this->rejectUnauthorized('publish-post', 'edit that post');
        } elseif ($this->userIsAuthorOf($post) && !Auth::user()->hasPermission($neededPermission)) {
            return $this->rejectUnauthorized($neededPermission);
        }
        return view('manage.posts.edit')->withPost($post)->withCategories(Category::all())->withTags($this->collectTags());
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $post = Post::find($id);
        $neededPermission = 'update-post';

        if (!$this->userIsAuthorOf($post) && !Auth::user()->hasPermission('publish-post')) {
            return $this->rejectUnauthorized('publish-post', 'edit that post');
        } elseif ($this->userIsAuthorOf($post) && !Auth::user()->hasPermission($neededPermission)) {
            return $this->rejectUnauthorized($neededPermission);
        } elseif ($request->submit_type == 'New tag') {
            return $this->saveTags($request);
        } elseif ($request->submit_type == 'Delete draft') {
            return $this->destroy($post->id);
        } elseif ($request->submit_type == 'Delete selected images') {
            foreach($request->image_id as $image_id){
                $image = $post->images()->where('id', $image_id);
                $image->delete();
            }
            Toaster::success("Image".count($request->image_id)==0 ? '' : 's'." deleted.");
            return redirect()->back();
        } elseif ($request->input('slug') == $post->slug) {
            $this->validate($request, [
                'title' => 'required|min:3|max:60',
                'body' => 'required|min:5|max:4000',
                'category_id' => 'required|numeric',
                'user_id' => 'required|numeric'
            ]);
        } else {
            $this->validatePostData($request);
        }
        $this->collectPostData($request, $post);
        $post = $this->setPostStatus($request, $post);
        $post->save();
        $post->tags()->sync($request->tags);
        Toaster::success("Post '" . $post->title . "' has been updated");
        Cache::forget('blog');
        if ($request->hasFile('images')) {
            $this->uploadImages($request, $post);
        }
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
        $neededPermission = 'delete-post';

        $post = Post::find($id);

        if (!$this->userIsAuthorOf($post) && !Auth::user()->hasPermission('publish-post')) {
            return $this->rejectUnauthorized('publish-post', 'delete that post');
        } elseif ($this->userIsAuthorOf($post) && !Auth::user()->hasPermission($neededPermission) && !Auth::user()->hasPermission('publish-post')) {
            return $this->rejectUnauthorized($neededPermission);
        }
        $post->tags()->detach();
        foreach ($post->comments as $comment) {
            $comment->delete();
        }
        $post->delete();

        Toaster::success('The post ' . "'" . "$post->title" . "'" . ' has been deleted!');
        Cache::forget('blog');
        return redirect()->route('posts.index');
    }

    /**
     * @param $posts
     * @return mixed
     */
    public function viewSorted($posts)
    {
        $published = $posts->where('status', 3);
        $submitted = $posts->where('status', 2);
        $drafts = $posts->where('status', 1);
        return view('manage.posts.index')->withPublished($published)->withSubmitted($submitted)->withDrafts($drafts);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveTags(Request $request)
    {
        $this->validate($request, [
            'tagSlug' => 'required|min:3|max:20'
        ]);
        $tag = new Tag;
        $tag->name = $request->tagSlug;
        $tag->save();
        Toaster::success("Tag was saved!");
        return redirect()->back();
    }

    /**
     * @param Request $request
     */
    public function validatePostData(Request $request): void
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
     *
     *
     * @param $post
     */
    public function collectPostData(Request $request, Post $post): void
    {
        $post->title = $request->title;
        $post->body = $request->body;
        $post->slug = $request->slug;
        $post->category_id = $request->category_id;
        $post->user_id = $request->user_id;
    }

    /**
     * @param Request $request
     * @param Post $post
     * @return Post
     */
    public function setPostStatus(Request $request, $post)
    {
        if ($request->submit_type == 'Save Draft') {
            $post->status = 1;
            Toaster::success("Draft has been saved!");
        } elseif ($request->submit_type == 'Submit') {
            $post->status = 2;
            Toaster::success("Post has been submitted!");
        } elseif ($request->submit_type == 'Publish' && Auth::user()->hasPermission('publish-post')) {
            $post->status = 3;
            $post->published_at = now();
            Toaster::success("Post has been published!");
        }
        return $post;
    }

    /**
     * @return array
     */
    public function collectTags(): array
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
     * @return bool
     */
    public function userIsAuthorOf($post): bool
    {
        return Auth::user()->id == $post->user->id;
    }

    /**
     * @param $request
     * @param $post
     */
    public function uploadImages(Request $request, $post)
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

}
