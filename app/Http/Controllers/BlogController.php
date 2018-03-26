<?php

namespace App\Http\Controllers;

use App\Category;
use App\Post;
use Toaster;
use Cache;

class BlogController extends Controller
{

    public function index()
    {
        $posts = Cache::remember('blog', 1440, function() {
            return $posts = Post::where('status', '=', 3)->orderBy('published_at', 'desc')->paginate(5);
        });

        $categories = Cache::remember('categories', 1440, function() {
            return $categories = Category::all();
        });
        return view('blog.index')->withPosts($posts)->withCategories($categories);
    }

    public function category($category_id)
    {
        $categories = Category::all();
        $posts = Post::where('category_id', $category_id)->where('status', '=', 3)->orderBy('id', 'desc')->paginate(5);

        return view('blog.index')->withPosts($posts)->withCategories($categories);
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
