@extends('layouts.app')

@section('content')


    {{ Html::linkRoute('blog.index', 'Go to blog', [],['class'=>'jumbotron btn btn-block', 'style'=>'font-size: 52px; font-weight:bold; background-color: rgb(192, 214, 228)']) }}

    <h2 style="font-weight: bold">Recent posts</h2>
    @foreach($posts as $post)
        <h3><strong>{{ Html::linkRoute('blog.show', $post->title, $post->slug, ['style'=>'color:#636b6f']) }}, </strong><small>posted {{ $post->created_at->diffForHumans() }}</small></h3>
        <h4>{{ substr($post->body, 0, 200) . (strlen($post->body)>200 ? '...' : '') }}</h4>
        <hr>
    @endforeach

@endsection

@section('panel-right')

    <h4 style="font-weight: bold">New users</h4>
    @foreach($users as $user)
        <h5>{{ $user->name }}</h5>
        <h6 style="margin-bottom:0px; margin-top:6px">Joined {{ $user->created_at->diffForHumans() }}</h6>
        <h6 style="margin-bottom:0px; margin-top:6px">Email:  {{ $user->email }}</h6>
        <h6 style="margin-bottom:0px; margin-top:6px">Posts created: {{ count($user->posts) }}</h6>
        <hr>
    @endforeach

    <h4 style="font-weight: bold">Recent comments</h4>
    @foreach($comments as $comment)
        <h5>{{Html::linkRoute('blog.show', 'In post "' . $comment->post->title . '", ' . $comment->created_at->diffForHumans(), $comment->post->slug, ['style'=>'color:inherit'])}}</h5>
        <h6><strong>{{ $comment->user_name }}: </strong>{{ substr($comment->body, 0, 100) . (strlen($comment->body)>100 ? '...' : '') }}</h6>
        <hr>
    @endforeach



@endsection
