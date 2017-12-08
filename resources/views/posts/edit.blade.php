@extends ('layouts.app')

@section('stylesheets')
    {!! Html::style('css/select2.min.css') !!}
@endsection

@section('content') <h1 class="title is-3">Edit post</h1>

{!! Form::model($post, ['route' => ['posts.update', $post->id], 'method' => 'PUT', 'files' => true]) !!}
{{ Form::label('title', 'Blog title:') }}
{{ Form::text('title', null, ['class'=>'input']) }}
<div class="columns" style="margin-bottom: 0px">
    <div class="column is-half">
        {{Form::label('tags', 'Tags:')}}
        {{ Form::select('tags[]', $tags, null, ['class' => 'select select2-multi is-fullwidth', 'multiple' => 'multiple']) }}

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

            <option {{ $category->id==$post->category_id ? "selected='selected'" : '' }} value="{{ $category->id }}">{{ $category->name }}</option>

        @endforeach
    </select>
</div>
<br>
{{ Form::label('body','Post text:') }}
{{ Form::textarea('body', null, ['class'=>'input', 'style'=>'min-height: 400px' ]) }}
{{ Form::file('image') }}
{{ Form::hidden('user_id', Auth::id() )}}
<br>
<div class="columns m-t-10">
    <div class="column is-half">
        <a class="button is-info is-outlined is-fullwidth" style="min-width: 200px" href="{{ URL::previous() }}">
    <span class="icon">
      <i class="fas fa-chevron-circle-left"></i>
    </span>
            <span>Cancel</span>
        </a>
    </div>
    <div class="column is-half">
        {{ Form::submit('Edit', ['name'=>'submit_type', 'class'=>'button is-fullwidth is-success']) }}
        {{ Form::close() }}

    </div>
</div>

@endsection

@section('scripts')

    {!! Html::script('js/select2.min.js') !!}

    <script type="text/javascript">
        $('.select2-multi').select2();
    </script>


@endsection