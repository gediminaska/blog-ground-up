<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use App\Category;
use Toaster;


class BlogController extends Controller
{
    public function index(){
        $posts=Post::where('status', '=', 3)->orderBy('id', 'desc')->paginate(5);
        $categories = Category::all();
        return view('blog.index')->withPosts($posts)->withCategories($categories);
    }

    public function category($category_id){
        $categories = Category::all();
        $posts=Post::where('category_id', $category_id)->where('status', '=', 3)->orderBy('id', 'desc')->paginate(5);

        return view('blog.index')->withPosts($posts)->withCategories($categories);

    }

    public function show($slug){
        $post=Post::where('slug', '=', $slug)->first();
        if($post->status == 3) {
            return view('blog.single')->withPost($post);
        }
        Toaster::danger('Post not published');
        return redirect()->route('blog.index');
    }
}
