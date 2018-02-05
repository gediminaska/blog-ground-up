<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use App\Category;


class BlogController extends Controller
{
    public function index(){
        $posts=Post::orderBy('id', 'desc')->paginate(5);
        $categories = Category::all();
        return view('blog.index')->withPosts($posts)->withCategories($categories);
    }

    public function category($category_id){
        $categories = Category::all();
        $posts=Post::where('category_id', $category_id)->orderBy('id', 'desc')->paginate(5);

        return view('blog.index')->withPosts($posts)->withCategories($categories);

    }

    public function show($slug){
        $post=Post::where('slug', '=', $slug)->first();
        $prev_url = url()->previous();
        return view('blog.single')->withPost($post)->withPrevUrl($prev_url);
    }
}
