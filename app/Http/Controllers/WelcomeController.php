<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use App\User;
use App\Comment;



class WelcomeController extends Controller
{
    public function index(){
        $posts=Post::orderBy('id', 'desc')->get()->take(5);
        $users=User::orderBy('id', 'desc')->get()->take(5);
        $comments=Comment::orderBy('id', 'desc')->get()->take(5);
        return view('welcome')->withPosts($posts)->withUsers($users)->withComments($comments);
    }
}
