@extends ('layouts.app')

@section('content')
    <a class="button is-info is-outlined" style="min-width: 200px" href="{{ route('blog.index') }}">
    <span class="icon">
      <i class="fa fa-arrow-left"></i>
    </span>
        <span>Back to blog</span>
    </a>
    <h2 class="title is-2">{{ $post->title }}</h2>
    <img src="{{ asset('images/' . $post->image) }}" style="display:block; margin: 0 auto; max-width: 80%">
    <h4 style="margin-top: 30px; text-align: justify">{{ $post->body }}</h4>
    <span style="margin-top:20px"><strong>Tags:</strong></span>
    @foreach($post->tags as $tag)
        <span class="tag is-dark">{{ $tag->name }}</span>
    @endforeach
    <br>

    @if(count($post->comments)>0)
        <hr>
        <span class="fa fa-comments-o fa-2x"></span><span class="title is-4"> Comments:</span><br>
    @endif

    @foreach($post->comments as $comment)
        <strong>{{ $comment->user_name }}</strong>
        <p>{{ $comment->body }}</p>
    @endforeach
    <hr>

        <h3 class="title is-4">Add a comment:</h3>
        {{ Form::open(['action'=>'CommentsController@store']) }}
        {{ Form::label('user_name', 'Your name:', ['class'=>'label']) }}
        {{ Form::text('user_name', $post->user->name, ['class'=>'control input', 'style'=>'max-width:500px']) }}
        {{ Form::label('body', 'Comment text:', ['class'=>'label m-t-10']) }}
        {{ Form::textarea('body', null, ['class'=>'control textarea', 'style'=>'max-width:500px; min-width:0']) }}
        {{ Form::submit('Post comment', ['class'=>'button is-success','style'=>'margin-top:10px']) }}
        {{ Form::hidden('post_id', $post->id) }}
        {{ Form::close() }}



@endsection