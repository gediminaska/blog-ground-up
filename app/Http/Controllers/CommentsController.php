<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Events\NewComment;
use App\Events\NewCommentInBlog;
use App\Events\UserTyping;
use App\Post;
use App\User;
use Illuminate\Http\Request;
use TheoryThree\LaraToaster\LaraToaster as Toaster;

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
        $user = $request->user;
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
        $comment->setAttribute('user_id', $request->get('api_token') ? User::query()->where('api_token', $request->get('api_token'))->first()->id : User::guestId);
        $comment->setAttribute('body', $request->get('body'));
        $comment->save();

        $comment = Comment::where('id', $comment->id)->with('user')->first();

        broadcast(new NewComment($comment))->toOthers();
        broadcast(new NewCommentInBlog($comment))->toOthers();

        return $comment->toJson();

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        foreach ($request->comment_id as $comment_id) {
            $comment = Comment::query()->where('id', $comment_id);
            $comment->delete();
        }
        $toaster = new Toaster;
        $toaster->success("Comments deleted.");
        return redirect()->back();

    }

}