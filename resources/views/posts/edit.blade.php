@extends ('layouts.app')

@section('stylesheets')
    {!! Html::style('css/select2.min.css') !!}
@endsection

@section('content')
    <h1>Edit post</h1>
    {{ Form::open(['route'=> ['posts.update', $post->id], 'method'=>'PUT']) }}
    {{ Form::label('title', 'Blog title:') }}
    {{ Form::text('title', $post->title, ['class'=>'form-control']) }}
    {{Form::label('tags', 'Tags:')}}
    <select class="form-control select2-multi" name="tags[]" multiple="multiple">
        @foreach($tags as $tag)

            <option value="{{ $tag->id }}">{{ $tag->name }}</option>

        @endforeach
    </select>

    {{ Form::text('name') }}
    {{ Form::submit('New tag', ['name'=>'submit_type']) }}
    <br>
    {{ Form::label('slug', 'Slug:') }}
    {{ Form::text('slug', $post->slug, ['class'=>'form-control']) }}
    {{ Form::label('category_id', 'Category:') }}

    {{ Form::select('category_id', $categories, $post->category_id, ['class'=>'form-control', 'style'=>'height: auto' ]) }}
    <br>
    {{ Form::label('body','Post text:') }}
    {{ Form::textarea('body', $post->body, ['class'=>'form-control']) }}
    {{ Form::hidden('user_id', Auth::id() )}}
    {{ Form::submit('Update', ['name'=>'submit_type']) }}
    {{ Form::close() }}
@endsection

@section('scripts')

    {!! Html::script('js/select2.min.js') !!}

    <script type="text/javascript">
        $('.select2-multi').select2();
    </script>


@endsection