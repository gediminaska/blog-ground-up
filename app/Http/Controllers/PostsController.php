<?php

namespace App\Http\Controllers;

use App\Category;
use App\Post;
use App\Repositories\Dashboard\DashboardRepository;
use App\Tag;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Imagine\Gmagick\Image;
use Toaster;


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
        $neededPermission = 'read-post';

        if (Auth::user()->hasPermission('read-all-posts')) {
            $posts = Post::orderBy('updated_at', 'desc')->get();
            return $this->viewSorted($posts);
        } elseif (Auth::user()->hasPermission($neededPermission)) {
            $posts = Post::query()->where('user_id', Auth::user()->id)->get();
            return $this->viewSorted($posts);
        }
        return $this->rejectUnauthorized($neededPermission);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create()
    {
        if (!Laratrust::can('create-post')) {
            return $this->rejectUnauthorized('create-post');
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
        return redirect()->route('posts.index');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function show($id)
    {
        $neededPermission = 'publish-post';

        $post = Post::query()->find($id);
        if (!Auth::user()->hasPermission($neededPermission) && !Auth::user()->id == $post->user->id) {
            return $this->rejectUnauthorized('to view this post');
        }
        return view('manage.posts.show')->withPost($post);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function edit($id)
    {
        $neededPermission = 'publish-post';

        $post = Post::query()->find($id);
        if (!Auth::user()->hasPermission($neededPermission) && !Auth::user()->id == $post->user->id) {
            return $this->rejectUnauthorized('to edit this post');
        }
        $categories = Category::all();
        $tags = $this->collectTags();
        return view('manage.posts.edit')->withPost($post)->withCategories($categories)->withTags($tags);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $neededPermission = 'publish-post';

        $post = Post::find($id);
        if (!Auth::user()->hasPermission($neededPermission) && !Auth::user()->id == $post->user->id) {
            return $this->rejectUnauthorized('to edit this post');
        } elseif ($request->submit_type == 'New tag') {
            return $this->saveTags($request);
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
        $neededPermission = 'publish-post';

        $post = Post::find($id);
        if (!Auth::user()->hasPermission($neededPermission) && !Auth::user()->id == $post->user->id) {
            return $this->rejectUnauthorized('to delete this post');
        }
        $post->tags()->detach();
        foreach ($post->comments as $comment) {
            $comment->delete();
        }
        $post->delete();

        Toaster::success('The post ' . "'" . "$post->title" . "'" . ' has been deleted!');
        return redirect()->route('posts.index');
    }

    public function apiCheckUnique(Request $request)
    {
        return json_encode(!Post::where('slug', '=', $request->slug)->exists());
    }

    public function apiGetStats(DashboardRepository $dashboardRepository)
    {
        $userActivity = $dashboardRepository->systemLastWeekActivities();
        $labels = [];
        $rows = [];

        foreach ($userActivity as $value) {
            $labels[] = $value->date;
            $rows[] = $value->count;
        }

        $data = [
            'labels' => $labels,
            'rows' => $rows,
        ];
        return response()->json(['data' => $data], 200);
    }

    public function apiGetCategoryStats(DashboardRepository $dashboardRepository)
    {
        $categoryStats = $dashboardRepository->systemCategoryStats();
        $labels = [];
        $rows = [];

        foreach ($categoryStats as $value) {
            $labels[] = Category::query()->find($value->category)->name;
            $rows[] = $value->count;
        }

        $data = [
            'labels' => $labels,
            'rows' => $rows,
        ];
        return response()->json(['data' => $data], 200);
    }

    public function apiGetUserStats(DashboardRepository $dashboardRepository)
    {
        $userStats = $dashboardRepository->systemUserStats();
        $labels = [];
        $rows = [];

        foreach ($userStats as $value) {
            $labels[] = User::query()->find($value->user)->name;
            $rows[] = $value->count;
        }

        $data = [
            'labels' => $labels,
            'rows' => $rows,
        ];
        return response()->json(['data' => $data], 200);
    }

    public function apiGetCommentStats(DashboardRepository $dashboardRepository)
    {
        $commentStats = $dashboardRepository->systemCommentStats();
        $labels = [];
        $rows = [];

        foreach ($commentStats as $value) {
            $labels[] = Post::query()->find($value->post)->slug;
            $rows[] = $value->count;
        }

        $data = [
            'labels' => $labels,
            'rows' => $rows,
        ];
        return response()->json(['data' => $data], 200);
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
            'name' => 'required|min:3|max:20'
        ]);
        $tag = new Tag;
        $tag->name = $request->name;
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

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $location = public_path('images/' . $filename);
            Image::make($image)->resize(800, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($location);

            $post->image = $filename;
        }
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
}
