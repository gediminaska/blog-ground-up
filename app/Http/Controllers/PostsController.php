<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use App\Category;

class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts=Post::all();
        return view('posts.index')->withPosts($posts);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories=Category::all();

        return view('posts.create')->withCategories($categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|min:5|max:60',
            'body' => 'required|min:10|max:4000',
            'slug' => 'required|min:3|unique:posts,slug',
            'category_id' => 'required|numeric',
            'user_id' =>'required|numeric'
        ]);
        $post = new Post;

        $post->title=$request->title;
        $post->body=$request->body;
        $post->slug=$request->slug;
        $post->category_id=$request->category_id;
        $post->user_id=$request->user_id;

        $post->save();

        return redirect()->route('posts.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post=Post::find($id);
        $categories=Category::all();
        foreach ($categories as $category) {
            $cats[$category->id]=$category->name;
        }
        return view('posts.edit')->withPost($post)->withCategories($cats);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
       $post=Post::find($id);

       if($request->input('slug')==$post->slug){
           $this->validate($request, [
               'title' => 'required|min:5|max:60',
               'body' => 'required|min:10|max:4000',
               'category_id' => 'required|numeric',
               'user_id' =>'required|numeric'
           ]);
       }
       else{
           $this->validate($request, [
               'title' => 'required|min:5|max:60',
               'body' => 'required|min:10|max:4000',
               'slug' => 'required|min:3|unique:posts,slug',
               'category_id' => 'required|numeric',
               'user_id' =>'required|numeric'
           ]);
       }

        $post->title=$request->title;
        $post->body=$request->body;
        $post->slug=$request->slug;
        $post->category_id=$request->category_id;
        $post->user_id=$request->user_id;

        $post->save();

        return redirect()->route('posts.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
