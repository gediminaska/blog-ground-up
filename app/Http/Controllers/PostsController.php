<?php

namespace App\Http\Controllers;

use App\Repositories\Dashboard\DashboardRepository;
use Illuminate\Http\Request;
use App\Post;
use App\Role;
use App\Category;
use App\Tag;
use App\User;
use Image;
use Session;
use Auth;
use Toaster;




class PostsController extends Controller
{
    public function __construct(){
        $this->middleware('role:superadministrator|administrator|author|editor');

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Auth::user()->hasRole('superadministrator||administrator') ? $posts = Post::all() : $posts=[];
        $categories=Category::all();
        return view('manage.posts.index')->withCategories($categories)->withPosts($posts);
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
            $this->validate($request, [
                'name' => 'required|min:3|max:20'
            ]);
            $tag = new Tag;
            $tag->name = $request->name;
            $tag->save();
            Toaster::success("Tag was saved!");
            return redirect()->back();
        }

        elseif ($request->submit_type == 'Save Draft') {
            $post = new Post;
            $post->status = 1;

        }

        elseif ($request->submit_type == 'Delete draft') {
            Toaster::success("Draft deleted.");
            return redirect()->route('posts.index');
        }

        elseif ($request->submit_type == 'Submit') {
            $post = new Post;
            $post->status = 2;
        }

        elseif ($request->submit_type == 'Publish' && Auth::user()->hasPermission('publish-post')) {
            $post = new Post;
            $post->status = 3;
            $post->published_at = now();
        }

        $this->validate($request, [
            'title' => 'required|min:3|max:60',
            'body' => 'required|min:5|max:4000',
            'slug' => 'required|min:3|unique:posts,slug',
            'category_id' => 'required|numeric',
            'user_id' => 'required|numeric'
        ]);

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

        if($post->status == 1) {
            Toaster::success("Draft has been saved!");
        } elseif ($post->status == 2) {
            Toaster::success("Post has been submitted!");
        } else {
            Toaster::success("Post has been published!");
        }
        $post->tags()->sync($request->tags, false);

        return redirect()->route('blog.index');


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
        else{
            return redirect()->route('posts.index');
        }
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
        else{
            return redirect()->route('posts.index');
        }
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
            Toaster::success("Tag was saved!");
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
        Toaster::success('The post ' . "'" . "$post->title" . "'" . ' has been updated!');


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

        $post=Post::find($id);
        $post->tags()->detach();
        foreach($post->comments as $comment){
            $comment->delete();
        }
        $post->delete();

        Toaster::success('The post ' . "'" . "$post->title" . "'" . ' has been deleted!');
        return redirect()->route('posts.index');

    }

    public function apiCheckUnique(Request $request) {
        return json_encode(!Post::where('slug', '=', $request->slug)->exists());
    }

    public function apiGetStats(DashboardRepository $dashboardRepository) {
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

 public function apiGetCategoryStats(DashboardRepository $dashboardRepository) {
        $categoryStats = $dashboardRepository->systemCategoryStats();
        $labels = [];
        $rows = [];

        foreach ($categoryStats as $value) {
            $labels[] = Category::find($value->category)->name;
            $rows[] = $value->count;
        }

        $data = [
            'labels' => $labels,
            'rows' => $rows,
        ];
        return response()->json(['data' => $data], 200);
    }

 public function apiGetUserStats(DashboardRepository $dashboardRepository) {
        $userStats = $dashboardRepository->systemUserStats();
        $labels = [];
        $rows = [];

        foreach ($userStats as $value) {
            $labels[] = User::find($value->user)->name;
            $rows[] = $value->count;
        }

        $data = [
            'labels' => $labels,
            'rows' => $rows,
        ];
        return response()->json(['data' => $data], 200);
    }

    public function apiGetCommentStats(DashboardRepository $dashboardRepository) {
        $commentStats = $dashboardRepository->systemCommentStats();
        $labels = [];
        $rows = [];

        foreach ($commentStats as $value) {
            $labels[] = Post::find($value->post)->slug;
            $rows[] = $value->count;
        }

        $data = [
            'labels' => $labels,
            'rows' => $rows,
        ];
        return response()->json(['data' => $data], 200);
    }
}
