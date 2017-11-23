@extends ('layouts.app')

@section('content')

    <h1>View post</h1>
    <h2>Title: {{ $post->title }}</h2>
    <h4>{{ $post->body }}</h4>
    @foreach($post->tags as $tag)
        <span class="badge">{{ $tag->name }}</span>
    @endforeach

    {{ Form::open(['action'=>'CommentsController@store']) }}
    {{ Form::label('user_name', 'Your name:') }}
    {{ Form::text('user_name', $post->user->name, ['class'=>'form-control']) }}
    {{ Form::label('body', 'Comment text:') }}
    {{ Form::textarea('body', null, ['class'=>'form-control']) }}
    {{ Form::submit('Post comment') }}
    {{ Form::hidden('post_id', $post->id) }}
    {{ Form::close() }}

    <h3>Comments:</h3>
    @foreach($post->comments as $comment)
        <strong>{{ $comment->user_name }}</strong>
        <p>{{ $comment->body }}</p>
    @endforeach
@endsection