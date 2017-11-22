@extends ('layouts.app')

@section('content')

    <h1>View post</h1>
    <h2>Title: {{ $post->title }}</h2>
    <h4>{{ $post->body }}</h4>
    @foreach($post->tags as $tag)
        <span>{{ $tag->name }}</span>
    @endforeach

@endsection