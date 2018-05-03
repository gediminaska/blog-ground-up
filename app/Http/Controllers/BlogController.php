<?php

namespace App\Http\Controllers;

use App\Category;
use App\Post;
use App\Tag;
use Cache;
use Toaster;

class BlogController extends Controller
{
    /**
     * @return mixed
     */
    public function index()
    {
        $tags = Tag::all()->pluck('name');
        if (request()->has('filter')) {
            $posts = Post::whereHas('tags', function ($q) {
                $selectedTags = explode(',', request('filter'));
                $q->where('name', $selectedTags[0]);
                array_shift($selectedTags);
                foreach ($selectedTags as $selectedTag) {
                    $q->orWhere('name', $selectedTag);
                }
            })->where('status', '=', 3)
                ->orderBy('published_at', 'desc')
                ->paginate(5);
        } else {
            $posts = Post::where('status', '=', 3)
                ->orderBy('published_at', 'desc')
                ->paginate(5);
        }

        $categories = Cache::remember('categories', 1440, function () {
            return $categories = Category::all();
        });
        return view('blog.index')
            ->withPosts($posts)
            ->withCategories($categories)
            ->withTags($tags);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function indexFiltered()
    {
        if(!is_array(request('filter'))) {
            return redirect()->route('blog.index');
        }
        $tags = Tag::all()->pluck('name');
        $posts = Post::where('status', '=', 3)
            ->whereHas('tags', function ($q) {
                $selectedTags = request('filter');
                $q->where('name', $selectedTags[0]);
                array_shift($selectedTags);
                foreach ($selectedTags as $selectedTag) {
                    $q->orWhere('name', $selectedTag);
                }
            })
            ->orderBy('published_at', 'desc')
            ->paginate(5);
        $categories = Cache::remember('categories', 1440, function () {
            return $categories = Category::all();
        });
        return view('blog.index')
            ->withPosts($posts)
            ->withCategories($categories)
            ->withTags($tags);
    }

    /**
     * @param $category_id
     * @return mixed
     */
    public function category($category_id)
    {

        $categories = Cache::remember('categories', 1440, function () {
            return $categories = Category::all();
        });
        $tags = Tag::all()->pluck('name');
        $posts = Post::where('category_id', $category_id)
            ->where('status', '=', 3)
            ->orderBy('id', 'desc')
            ->paginate(5);

        return view('blog.index')
            ->withPosts($posts)
            ->withCategories($categories)
            ->withTags($tags);
    }

    /**
     * @param $category_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function categoryFiltered($category_id)
    {
        if(!is_array(request('filter'))) {
            return redirect()->route('blog.category', $category_id);
        }

        $categories = Cache::remember('categories', 1440, function () {
            return $categories = Category::all();
        });

        $tags = Tag::all()->pluck('name');
        $posts = Post::where('category_id', $category_id)
            ->where('status', '=', 3)
            ->whereHas('tags', function ($q) {
                $selectedTags = request('filter');
                $q->where('name', $selectedTags[0]);
                array_shift($selectedTags);
                foreach ($selectedTags as $selectedTag) {
                    $q->orWhere('name', $selectedTag);
                }
            })
            ->orderBy('id', 'desc')->paginate(5);

        return view('blog.index')
            ->withPosts($posts)
            ->withCategories($categories)
            ->withTags($tags);
    }

    /**
     * @param $slug
     * @return \Illuminate\Http\RedirectResponse
     */
    public function show($slug)
    {
        $post = Post::where('slug', $slug)->first();
        if ($post->status == 3) {
            return view('blog.single')->withPost($post);
        }
        Toaster::danger('Post not published');
        return redirect()->route('blog.index');
    }

    /**
     * @return mixed
     */
    public function search() {

        return view('blog.search');
    }
}
