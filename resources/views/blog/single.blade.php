@extends ('layouts.app')

@section('content')
    {{ Html::linkRoute('blog.index', 'Back to blog', [], ['class'=>'btn btn-primary', 'style'=>'background-color:rgb(238, 238, 238); color:inherit; border-color: rgb(200, 238, 255); min-width: 200px ']) }}
    <h2>Title: {{ $post->title }}</h2>
    <img src="{{ asset('images/' . $post->image) }}" style="display:block; margin: 0 auto">
    <h4 style="margin-top: 30px; text-align: justify">{{ $post->body }}</h4>
    <h5 style="margin-top:20px"><strong>Tags:</strong></h5>
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
    {{ Form::submit('Post comment', ['style'=>'margin-top:10px']) }}
    {{ Form::hidden('post_id', $post->id) }}
    {{ Form::close() }}
@endsection