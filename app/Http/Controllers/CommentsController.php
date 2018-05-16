<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Comment;
use App\User;
use TheoryThree\LaraToaster\LaraToaster as Toaster;
use App\Post;
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
        $comments = Comment::query()->latest()->take(5)->with('post')->with('user')->get();
        return response()->json($comments);
    }

    /**
     * @param $id
     * @param Request $request
     */
        public function typing($id, Request $request)
    {
        $user = $request->get('user');
        broadcast(new UserTyping($id, $user))->toOthers();

    }

    /**
     * @param Request $request
     * @return string
     */
    public function store(Request $request)
    {

        $this->validate($request, [
            'body' => 'required|min:2|max:500',
        ]);
        $comment = new Comment;

        $comment->setAttribute('post_id', $request->get('post_id'));
        /* Hard-coded Guest user_id in the next line */
        $comment->setAttribute('user_id', $request->get('api_token') ? User::query()->where('api_token', $request->get('api_token'))->first()->id : 2);
        $comment->setAttribute('body', $request->get('body'));
        $comment->save();

        $comment = Comment::query()->find($comment->getAttribute('id'))->with('user')->first();

        broadcast(new NewComment($comment))->toOthers();
        broadcast(new NewCommentInBlog($comment))->toOthers();

        return $comment->toJson();

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Comment::query()->find($id)->delete();

        $toaster = new Toaster;
        $toaster->success('The comment has been deleted!');
        return redirect()->back();

    }

}
