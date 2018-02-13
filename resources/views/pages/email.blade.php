@extends('layouts.app')
@section('title', ' | Email')
@section('content')

<h1 class="title">Email app</h1>
    <div class="subtitle">This app allows you to send emails that appear to be sent by someone else!</div>
<hr>

<form action="{{ route('send.email') }}" method="POST">
    {{ csrf_field() }}
    <div class="field">
        <label for="sender" class="label">Sender email address: </label>
        <input type="text" class="input" id="sender" name="sender" aria-describedby="emailHelp" placeholder="Enter any email" style="max-width: 450px"/>
        <p id="emailHelp" class="help" style="font-style: italic"><strong style="font-style: normal">Example: </strong>tarkim.gediminas@gmail.com</p>
    </div>
    <div class="field">
        <label for="senderName" class="label">Sender name: </label>
        <input type="text" class="input" id="senderName" name="senderName" size ="10" aria-describedby="emailHelp" placeholder="Enter name" style="max-width: 450px"/>
        <p id="emailHelp" class="help" style="font-style: italic"><strong style="font-style: normal">Example: </strong>Gediminas K</p>
    </div>
    <div class="field">
        <label for="receiver" class="label">Receiver email adress (try your own first): </label>
        <input type="email" class="input" id="receiver" name="receiver" size ="10" aria-describedby="emailHelp" placeholder="Enter email" style="max-width: 450px"/>
        <p id="emailHelp" class="help" style="font-style: italic"><strong style="font-style: normal">Example: </strong>tarkim.gediminas@gmail.com</p>
    </div>
    <div class="field">
        <label for="subject" class="label">Subject: </label>
        <input type="text" class="input" id="subject" name="subject" placeholder="Enter subject" style="max-width: 450px"/>
    </div>
    <div class="field">
        <label for="bodyMessage" class="label">Message: </label>
        <textarea class="textarea" id="bodyMessage" name="bodyMessage" cols="2" style="max-width:450px; min-width: 0px"></textarea>
    </div>
    <button class="button is-success is-large" name="submit" style="margin-bottom: 20px">Send</button>

</form>

@endsection

