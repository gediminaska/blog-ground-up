@extends ('layouts.app')

@section('stylesheets')
    {!! Html::style('css/select2.min.css') !!}
@endsection

@section('content')
    <h1 class="title is-3">New post</h1>

    {{ Form::open(['action'=> 'PostsController@store', 'method'=>'POST', 'files' => true]) }}
        {{ Form::label('title', 'Blog title:') }}
        {{ Form::text('title', null, ['class'=>'input']) }}
    <div class="columns" style="margin-bottom: 0px">
        <div class="column is-half">
            {{Form::label('tags', 'Tags:')}}
            <select class="select2-multi input is-fullwidth" name="tags[]" multiple="multiple">
                @foreach($tags as $tag)

                    <option value="{{ $tag->id }}" class="is-fullwidth">{{ $tag->name }}</option>

                @endforeach
            </select>
        </div>
        <div class="column is-one-third">
            {{ Form::label('name', 'Create new tag:') }}
            {{ Form::text('name', null ,['class'=>'input']) }}
        </div>
        <div class="column m-t-25">
            {{ Form::submit('New tag', ['name'=>'submit_type', 'class'=>'button is-success is-fullwidth']) }}
        </div>
    </div>
        {{ Form::label('slug', 'Slug:') }}
        {{ Form::text('slug', null, ['class'=>'input']) }}
        {{ Form::label('category_id', 'Category:') }}
<br>
        <div class="select">
            <select name="category_id" id="category_id" style="min-width: 300px">
                    @foreach($categories as $category)

                        <option value="{{ $category->id }}">{{ $category->name }}</option>

        @endforeach
            </select>
        </div>
    <br>
        {{ Form::label('body','Post text:') }}
        {{ Form::textarea('body', null, ['class'=>'input', 'style'=>'min-height: 400px' ]) }}
        {{ Form::file('image') }}
        {{ Form::hidden('user_id', Auth::id() )}}
    <br>
        {{ Form::submit('Create', ['name'=>'submit_type', 'class'=>'button is-fullwidth is-success m-t-10']) }}
    {{ Form::close() }}
@endsection

@section('scripts')

    {!! Html::script('js/select2.min.js') !!}

    <script type="text/javascript">
        $('.select2-multi').select2();
    </script>


@endsection