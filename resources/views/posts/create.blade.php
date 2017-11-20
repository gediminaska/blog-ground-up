@extends ('layouts.app')

@section('content')
    <h1>New post</h1>
    {{ Form::open(['action'=> 'PostsController@store', 'method'=>'POST']) }}
        {{ Form::label('title', 'Blog title:') }}
        {{ Form::text('title', null, ['class'=>'form-control']) }}
        {{ Form::label('slug', 'Slug:') }}
        {{ Form::text('slug', null, ['class'=>'form-control']) }}
        {{ Form::label('category_id', 'Category:') }}

        <select class="form-control" name="category_id" style="height: auto">
                    @foreach($categories as $category)

                        <option value="{{ $category->id }}" class="form-control">{{ $category->name }}</option>

        @endforeach
        </select>
        {{ Form::label('body','Post text:') }}
        {{ Form::textarea('body', null, ['class'=>'form-control']) }}
        {{ Form::hidden('user_id', Auth::id() )}}
        {{ Form::submit('Create') }}
    {{ Form::close() }}
@endsection