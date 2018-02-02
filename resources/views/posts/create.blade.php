@extends ('layouts.app')

@section('stylesheets')
    {!! Html::style('css/select2.min.css') !!}
@endsection

@section('content')


    <h1 class="title is-3">New post</h1>

    {{ Form::open(['action'=> 'PostsController@store', 'method'=>'POST', 'files' => true]) }}

    <div id="app">
        <b-field>
            <b-input type="text" placeholder="Post Title" size="is-large" v-model="title" name="title">
            </b-input>
        </b-field>

        <slug-widget url="{{url('/')}}" subdirectory="/blog" :title="title" @slug-changed="updateSlug"></slug-widget>
        <input type="hidden" v-model="slug" name="slug" />
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
        {{ Form::label('category_id', 'Category:') }}
<br>
        <div class="select">
            <select name="category_id" id="category_id">
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
        {{ Form::submit('Create', ['name'=>'submit_type', 'class'=>'button']) }}
    {{ Form::close() }}
    </div>

@endsection

@section('scripts')
    <script>

        var app = new Vue({
            el: '#app',
            data: {
                title: '',
                slug: ''
            },
            methods: {
                updateSlug: function(val) {
                    this.slug = val;
                }
            }
        });
    </script>

    {!! Html::script('js/select2.min.js') !!}

    <script type="text/javascript">
        $('.select2-multi').select2();
    </script>


@endsection