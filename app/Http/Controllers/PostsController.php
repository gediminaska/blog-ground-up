<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use App\Category;
use App\Tag;
use Image;
use Session;
use Auth;



class PostsController extends Controller
{
    public function __construct(){
        $this->middleware('auth');

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $categories=Category::all();
        return view('manage.posts.index')->withCategories($categories);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories=Category::all();
        $tags=Tag::orderBy('name', 'asc')->get();

        return view('manage.posts.create')->withCategories($categories)->withTags($tags);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->submit_type=='New tag') {
            $tag = new Tag;
            $tag->name = $request->name;
            $tag->save();
            return redirect()->back();
        }

        else {
            $this->validate($request, [
                'title' => 'required|min:3|max:60',
                'body' => 'required|min:5|max:4000',
                'slug' => 'required|min:3|unique:posts,slug',
                'category_id' => 'required|numeric',
                'user_id' => 'required|numeric'
            ]);
            $post = new Post;

            $post->title = $request->title;
            $post->body = $request->body;
            $post->slug = $request->slug;
            $post->category_id = $request->category_id;
            $post->user_id = $request->user_id;

            if($request->hasFile('image')){
                $image = $request->file('image');
                $filename = time() . '.' . $image->getClientOriginalExtension();
                $location = public_path('images/' . $filename);
                Image::make($image)->resize(800, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($location);

                $post->image = $filename;
            }


            $post->save();

            $post->tags()->sync($request->tags, false);
            Session::flash('success', 'The post has been saved!');
            return redirect()->route('blog.index');
        }

    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post=Post::find($id);
        if (Auth::user()->id == $post->user->id) {
        return view('manage.posts.show')->withPost($post);
        }
        else{return redirect()->route('manage.posts.index');}
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $post = Post::find($id);
        if (Auth::user()->id == $post->user->id) {
            $categories = Category::all();
            $tags = Tag::orderBy('name', 'asc')->get();
            $tags2 = array();
            foreach ($tags as $tag) {
                $tags2[$tag->id] = $tag->name;
            }
            return view('manage.posts.edit')->withPost($post)->withCategories($categories)->withTags($tags2);
        }
        else{return redirect()->route('manage.posts.index');}
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
        if ($request->submit_type=='New tag') {
            $this->validate($request, [
                'name' => 'required|min:3|max:30',
                ]);
            $tag = new Tag;
            $tag->name = $request->name;
            $tag->save();
            Session::flash('success', 'New tag has been created!');
            return redirect()->back();
        }

        elseif($request->input('slug')==$post->slug){
           $this->validate($request, [
               'title' => 'required|min:3|max:60',
               'body' => 'required|min:5|max:4000',
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

        if($request->hasFile('image')){
            $image = $request->file('image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $location = public_path('images/' . $filename);
            Image::make($image)->resize(800, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($location);

            $post->image = $filename;
        }

        $post->save();

        $post->tags()->sync($request->tags);

        Session::flash('success', 'The post ' . "'" . "$post->title" . "'" . ' has been updated!');

        return redirect()->route('manage.posts.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post=Post::find($id);
        $post->tags()->detach();
        foreach($post->comments as $comment){
            $comment->delete();
        }
        $post->delete();

        Session::flash('success', 'The post ' . "'" . "$post->title" . "'" . ' has been deleted!');
        return redirect()->route('manage.posts.index');

    }
}
