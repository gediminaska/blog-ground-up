@extends ('layouts.app')

@section('content')
    <h1>Edit post</h1>
    {{ Form::open(['route'=> ['posts.update', $post->id], 'method'=>'PUT']) }}
    {{ Form::label('title', 'Blog title:') }}
    {{ Form::text('title', $post->title, ['class'=>'form-control']) }}
    {{ Form::label('slug', 'Slug:') }}
    {{ Form::text('slug', $post->slug, ['class'=>'form-control']) }}
    {{ Form::label('category_id', 'Category:') }}

    {{ Form::select('category_id', $categories, $post->category_id, ['class'=>'form-control', 'style'=>'height: auto' ]) }}
    <br>
    {{ Form::label('body','Post text:') }}
    {{ Form::textarea('body', $post->body, ['class'=>'form-control']) }}
    {{ Form::hidden('user_id', Auth::id() )}}
    {{ Form::submit('Update') }}
    {{ Form::close() }}
@endsection