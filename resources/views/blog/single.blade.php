@extends ('layouts.app')

@section('content')
    {{ Html::linkRoute('blog.index', 'Back to blog', [], ['class'=>'button is-info is-outlined', 'style'=>'min-width: 200px ']) }}
    <h2 class="title is-2">{{ $post->title }}</h2>
    <img src="{{ asset('images/' . $post->image) }}" style="display:block; margin: 0 auto; max-width: 80%">
    <h4 style="margin-top: 30px; text-align: justify">{{ $post->body }}</h4>
    <span style="margin-top:20px"><strong>Tags:</strong></span>
    @foreach($post->tags as $tag)
        <span class="tag is-dark">{{ $tag->name }}</span>
    @endforeach
    <br>
    <br>
    @if(count($post->comments)>0)
        <h3 class="title is-5">Comments:</h3>
    @endif

    @foreach($post->comments as $comment)
        <strong>{{ $comment->user_name }}</strong>
        <p>{{ $comment->body }}</p>
    @endforeach
    <h3 class="title is-5">Add a comment:</h3>
    {{ Form::open(['action'=>'CommentsController@store']) }}
    {{ Form::label('user_name', 'Your name:') }}
    {{ Form::text('user_name', $post->user->name, ['class'=>'control input', 'style'=>'max-width:500px']) }}
    {{ Form::label('body', 'Comment text:') }}
    {{ Form::textarea('body', null, ['class'=>'control textarea', 'style'=>'max-width:500px']) }}
    {{ Form::submit('Post comment', ['class'=>'button is-success','style'=>'margin-top:10px']) }}
    {{ Form::hidden('post_id', $post->id) }}
    {{ Form::close() }}

@endsection