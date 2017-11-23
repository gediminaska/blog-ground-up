@extends ('layouts.app')

@section('content')

    <h1>View post</h1>
    <h2>Title: {{ $post->title }}</h2>
    <h4>{{ $post->body }}</h4>
    @foreach($post->tags as $tag)
        <span class="badge">{{ $tag->name }}</span>
    @endforeach

    <h3>Comments:</h3>
    @foreach($post->comments as $comment)
        <strong>{{ $comment->user->name }}</strong>
        <p>{{ $comment->body }}</p>
    @endforeach
@endsection