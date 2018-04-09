<?php

namespace App\Http\Controllers;

use App\Category;
use App\Post;
use App\Tag;
use Toaster;
use Cache;

class BlogController extends Controller
{

    public function index()
    {
        Cache::forget('blog');
        $tags = Tag::all()->pluck('name');
        $posts = Cache::remember('blog', 1440, function() {
            return $posts = Post::with('comments', 'user', 'category')->where('status', '=', 3)->orderBy('published_at', 'desc')->paginate(5);
        });

        $categories = Cache::remember('categories', 1440, function() {
            return $categories = Category::all();
        });
        return view('blog.index')->withPosts($posts)->withCategories($categories)->withTags($tags);
    }

    public function category($category_id)
    {
        $categories = Category::all();
        $tags = Tag::all();
        $posts = Post::with('user', 'comments')->where('category_id', $category_id)->where('status', '=', 3)->orderBy('id', 'desc')->paginate(5);

        return view('blog.index')->withPosts($posts)->withCategories($categories)->withTags($tags);
    }

    public function show($slug)
    {
        $post = Post::where('slug', $slug)->first();
        if ($post->status == 3) {
            return view('blog.single')->withPost($post);
        }
        Toaster::danger('Post not published');
        return redirect()->route('blog.index');
    }
}
