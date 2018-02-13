@extends('layouts.app')

@section('title', ' | Contact me')

@section('content')

    <h1 class="title">Contact Me</h1>
    <hr>

    <form action="{{route('send.email')}}" method="POST">
        {{ csrf_field() }}
        <input type="hidden" name="receiver" value="tarkim.gediminas@gmail.com">
        <input type="hidden" name="subject" value="Message from Contact form in the Site">
        <input type="hidden" name="sentFromForm" value="true">
        <div class="field" style="display: inline-block; margin-right: 60px;">
            <label for="senderName" class="label">Name</label>
            <div class="control">
                <input type="text" class="input" name="senderName" placeholder="Enter your name">
            </div>
        </div>
        <div class="field" style="display: inline-block">
            <label for="sender" class="label">Email</label>
            <div class="control">
                <input type="text" class="input" name="sender" placeholder="Enter your email">
            </div>
        </div>
        <div class="field" style="max-width: 600px;"><label for="bodyMessage" class="label">Message</label>
            <div class="control"><textarea name="bodyMessage" cols="30" rows="10" class="textarea"
                                           placeholder="Enter your message here"></textarea>
            </div>
        </div>
        <button class="button is-success is-large">Send</button>
    </form>
@stop