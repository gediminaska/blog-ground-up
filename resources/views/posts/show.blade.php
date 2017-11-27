@extends ('layouts.app')

@section('content')

    <h1>View post</h1>
    <h2>Title: {{ $post->title }}</h2>
    <img src="{{ asset('images/' . $post->image) }}">
    <h4>{{ $post->body }}</h4>
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
        {{ Form::open(['route'=> ['comments.delete', $comment->id], 'method'=>'DELETE'])}}
        {{ Form::submit('Delete comment') }}
        {{ Form:: close() }}
    @endforeach
    <br>
    {{ Html::linkRoute('posts.index', 'Return to posts',[],['class'=>'btn btn-primary', 'style'=>'margin-top:30px']) }}
@endsection