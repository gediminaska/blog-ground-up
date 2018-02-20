@extends ('layouts.manage')

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


@endsection

@section('panel-right')
    <div class="card card-widget">
        <div class="author-widget widget-area">
            <div class="selected-author">
                <img src="https://placehold.it/50x50"/>
                <div class="author">
                    <h4>{{Auth::user()->name}}</h4>
                    <p class="subtitle">
                        (Author)
                    </p>
                </div>
            </div>
        </div>
        <div class="post-status-widget widget-area">
            <div class="status">
                <div class="status-icon">
                    <b-icon icon="file" size="is-medium"></b-icon>
                </div>
                <div class="status-details">
                    <h4><span class="status-emphasis">{{$post->status == 1 ? "Draft " : "Post "}}</span>{{$post->status == 1 ? "saved:" : ($post->status == 2 ? "submitted for review:" : "published:")}}</h4>
                    <p>{{$post->status == 1 ? $post->updated_at->diffForHumans() : $post->status == 2 ? $post->updated_at->diffForHumans() : $post->published_at->diffForHumans()}}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="columns m-t-10">
        <div class="column">
            <button class="button is-info is-outlined is-fullwidth" name="submit_type" value="Save Draft">Save {{$post->status == 1 ? '' : 'as'}} draft</button>
        </div>
        <div class="column">
            <button class="button is-danger is-fullwidth" name="submit_type" value="Delete draft">Delete</button>
        </div>
    </div>

    @if(Auth::user()->hasPermission('publish-post'))
        {{ Form::submit($post->status == 3 ? 'Publish again' : 'Publish', ['name'=>'submit_type', 'class'=>'button is-success is-fullwidth m-t-10']) }}
    @else
        {{ Form::submit('Submit again', ['name'=>'submit_type', 'class'=>'button is-primary is-fullwidth m-t-10']) }}
    @endif

    {{ Form::close() }}

@endsection

@section('scripts')

    {!! Html::script('js/select2.min.js') !!}

    <script type="text/javascript">
        $('.select2-multi').select2();
    </script>


@endsection