@extends ('layouts.manage')

@section('content')

    <a class="button is-info is-outlined" style="min-width: 200px" href="{{ route('posts.index') }}">
        <span class="icon">
          <i class="fas fa-chevron-circle-left"></i>
        </span>

        <span>Go back</span>
    </a>
    <h2 class="title is-2">{{ $post->title }}</h2>
    <img src="{{ asset('images/' . $post->image) }}" style="display:block; margin: auto; width: 100%">
    <h4 style="margin:auto; font-size:larger; margin-top: 30px; text-align: justify; white-space: pre-line; max-width:700px">{{ $post->body }}</h4>
    <br><span style="margin-top:20px"><strong>Tags:</strong></span>
    @foreach($post->tags as $tag)
        <span class="tag is-dark">{{ $tag->name }}</span>
    @endforeach
    <br>

    @if(count($post->comments)>0)
        <hr>
        <span class="far fa-comments fa-2x"></span><span class="title is-4"> Comments:</span><br>
    @endif


    @foreach($post->comments as $comment)
        <strong>{{ $comment->user_name }}</strong>
        <p>{{ $comment->body }}</p>
        {{ Form::open(['route'=> ['comments.delete', $comment->id], 'method'=>'DELETE'])}}
        {{ Form::submit('Delete comment', ['class'=>'button is-danger']) }}
        {{ Form:: close() }}
        <br>
    @endforeach
    <br>
    <a class="button is-info is-outlined" style="min-width: 200px" href="{{ route('posts.index') }}">
    <span class="icon">
      <i class="fas fa-chevron-circle-left"></i>
    </span>
        <span>Go back</span>
    </a>
@endsection