@extends('layouts.app')

@section('content')
    <p class="field">
        <a href="{{ route('blog.index') }}" class="button is-large is-primary is-fullwidth" style="padding: 40px">
            <span class="icon is-large">
                <i class="fa fa-rss"></i>
            </span>
            <span>
                Go to Blog
            </span>
        </a>
    </p>

    <h2 class="title is-2">Recent posts</h2>
    @foreach($posts as $post)
        <h3 class="title is-5">{{ Html::linkRoute('blog.show', $post->title, $post->slug, ['style'=>'color:inherit']) }}</h3> <h3 class="subtitle is-7">posted {{ $post->created_at->diffForHumans() }}</h3>
        <h4>{{ substr($post->body, 0, 200) . (strlen($post->body)>200 ? '...' : '') }}</h4>
        <hr>
    @endforeach

@endsection

@section('panel-right')

    <h4 class="title is-4">New users</h4>
    @foreach($users as $user)
        <h5 class="title is-6">{{ $user->name }}</h5>
        <h6 style="margin-bottom:0px; margin-top:6px">Joined {{ $user->created_at->diffForHumans() }}</h6>
        <h6 style="margin-bottom:0px; margin-top:6px">Email:  {{ $user->email }}</h6>
        <h6 style="margin-bottom:0px; margin-top:6px">Posts created: {{ count($user->posts) }}</h6>
        <hr>
    @endforeach

    <h4 class="title is-4">Recent comments</h4>
    @foreach($comments as $comment)
        <h5>{{Html::linkRoute('blog.show', 'In post "' . $comment->post->title . '", ' . $comment->created_at->diffForHumans(), $comment->post->slug, ['style'=>'color:inherit'])}}</h5>
        <h6><strong>{{ $comment->user_name }}: </strong>{{ substr($comment->body, 0, 100) . (strlen($comment->body)>100 ? '...' : '') }}</h6>
        <hr>
    @endforeach



@endsection
