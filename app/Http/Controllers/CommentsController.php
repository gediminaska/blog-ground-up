<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Comment;
use App\User;
use Session;
use App\Post;
use Auth;
use App\Events\UserTyping;
use App\Events\NewComment;
use App\Events\NewCommentInBlog;

class CommentsController extends Controller
{
    /**
     * @param Post $post
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Post $post)
    {
        return response()->json($post->comments()->latest()->with('user')->get());
    }

    public function latest()
    {
        $comments = Comment::latest()->take(5)->with('post')->with('user')->get();
        return response()->json($comments);
    }

    /**
     * @param $id
     * @param Request $request
     */
        public function typing($id, Request $request)
    {
        $user = $request->user;
        broadcast(new UserTyping($id, $user))->toOthers();

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
            'body' => 'required|min:2|max:500',
        ]);
        $comment = new Comment;

        $comment->post_id = $request->post_id;

        /* Hard-coded Guest user_id in the next line */
        $comment->user_id = $request->api_token ? User::where('api_token', $request->api_token)->first()->id : 2;

        $comment->body = $request->body;

        $comment->save();

        $comment = Comment::where('id', $comment->id)->with('user')->first();

        broadcast(new NewComment($comment))->toOthers();
        broadcast(new NewCommentInBlog($comment))->toOthers();

        return $comment->toJson();

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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Comment::find($id)->delete();

        Session::flash('success', 'The comment has been deleted!');
        return redirect()->back();

    }

}
