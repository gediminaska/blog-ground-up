@extends ('layouts.app')

@section('content')

    <h1>View post</h1>
    <h2>Title: {{ $post->title }}</h2>
    <img src="{{ asset('images/' . $post->image) }}">
    <h4 style="margin-top: 30px">{{ $post->body }}</h4>
    <h5>Tags:</h5>
    @foreach($post->tags as $tag)
        <span class="badge">{{ $tag->name }}</span>
    @endforeach


    @if(count($post->comments)>0)
        <h3>Comments:</h3>
    @endif

    @foreach($post->comments as $comment)
        <strong>{{ $comment->user_name }}</strong>
        <p>{{ $comment->body }}</p>
    @endforeach
    <h3>Add a comment:</h3>
    {{ Form::open(['action'=>'CommentsController@store']) }}
    {{ Form::label('user_name', 'Your name:') }}
    {{ Form::text('user_name', $post->user->name, ['class'=>'form-control', 'style'=>'max-width:500px']) }}
    {{ Form::label('body', 'Comment text:') }}
    {{ Form::textarea('body', null, ['class'=>'form-control', 'style'=>'max-width:500px']) }}
    {{ Form::submit('Post comment') }}
    {{ Form::hidden('post_id', $post->id) }}
    {{ Form::close() }}
@endsection