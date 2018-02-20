<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use App\User;
use App\Comment;
use Toaster;
use Illuminate\Support\Facades\Mail;



class PagesController extends Controller
{
    public function index(){
        $posts=Post::where('status', 3)->orderBy('id', 'desc')->get()->take(5);
        $users=User::orderBy('id', 'desc')->get()->take(5);
        $comments=Comment::orderBy('id', 'desc')->get()->take(5);
        return view('welcome')->withPosts($posts)->withUsers($users)->withComments($comments);
    }

    public function email(){
        return view('pages.email');
    }

    public function sendEmail(Request $request){
        $this->validate($request, [

            'receiver'=>'required|email',
            'subject' => 'min:3',
            'bodyMessage' => 'min:5']);

        $data = array(
            'sender' => $request->sender,
            'senderName' => $request->senderName,
            'receiver' => $request->receiver,
            'subject' => $request->subject,
            'bodyMessage' => $request->bodyMessage
        );

        $sentFromForm = $request->sentFromForm;

        Mail::send(['text'=>'emails.send'], $data, function($message) use ($data){
            $message->from($data['sender'], $data['senderName']);
            $message->to($data['receiver']);
            $message->subject($data['subject']);

        });
        if($sentFromForm == 'true'){
            Toaster::success("Contact form has been sent!");
            return redirect()->route('welcome');
        }

        else{
            Toaster::success("Message sent.");
            return redirect()->route('welcome');
        }

    }

    public function contact(){
        return view('pages.contact');
    }
}
