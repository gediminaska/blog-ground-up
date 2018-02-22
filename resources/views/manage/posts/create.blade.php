@extends ('layouts.manage')

@section('stylesheets')
    {!! Html::style('css/select2.min.css') !!}
@endsection

@section('content')
    {{ Form::open(['action'=> 'PostsController@store', 'method'=>'POST', 'files' => true]) }}

    <div id="app">
        {{csrf_field()}}
        <b-field>
            <b-input type="text" placeholder="Post Title" size="is-large" v-model="title" name="title">
            </b-input>
        </b-field>

        <slug-widget url="{{url('/')}}" subdirectory="/blog" :title="title" @slug-changed="updateSlug"></slug-widget>
        <input type="hidden" v-model="slug" name="slug"/>
        <div class="columns" style="margin-bottom: 0px">
            <div class="column is-half">
                {{Form::label('tags', 'Tags:')}}
                <select class="select2-multi input" name="tags[]" multiple="multiple" style="border: inherit;">
                    @foreach($tags as $tag)

                        <option value="{{ $tag->id }}" class="is-fullwidth">{{ $tag->name }}</option>

                    @endforeach
                </select>
            </div>
            <div class="column is-one-third">
                {{ Form::label('name', 'New tag:') }}
                {{ Form::text('name', null ,['class'=>'input']) }}
            </div>
            <div class="column m-t-25">
                {{ Form::submit('New tag', ['name'=>'submit_type', 'class'=>'button is-primary is-fullwidth']) }}
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
    </div>

@endsection
@section('panel-right')
        <div class="card card-widget">
            <div class="author-widget widget-area">
                <div class="selected-author">
                    <img src="https://www.gravatar.com/avatar/{{md5(Auth::user()->email)}}?s=50"/>
                    <div class="author">
                        <h4>{{Auth::user()->name}}</h4>
                        <p class="subtitle">
                            {{Auth::user()->roles->pluck('display_name')->first()}}
                        </p>
                    </div>
                </div>
            </div>
            <div class="post-status-widget widget-area">
                <div class="status">
                    <div class="status-icon">
                        <b-icon icon="fa-user" size="is-medium"></b-icon>
                    </div>
                    <div class="status-details">
                        <h4><span class="status-emphasis">Draft</span> Saved</h4>
                        <p>A Few Minutes Ago</p>
                    </div>
                </div>
            </div>
        </div>
    <div class="columns m-t-10">
        <div class="column">
            <button class="button is-info is-outlined is-fullwidth" name="submit_type" value="Save Draft">Save Draft</button>
        </div>
        <div class="column">
            <button class="button is-danger is-fullwidth" name="submit_type" value="Delete draft">Delete draft</button>
        </div>
    </div>

    @if(Auth::user()->hasPermission('publish-post'))
        {{ Form::submit('Publish', ['name'=>'submit_type', 'class'=>'button is-success is-fullwidth m-t-10']) }}
    @else
        {{ Form::submit('Submit', ['name'=>'submit_type', 'class'=>'button is-primary is-fullwidth m-t-10']) }}
    @endif

    {{ Form::close() }}

@endsection


@section('scripts')
    <script>

        var app = new Vue({
            el: '#app',
            data: {
                title: '',
                slug: '',
                api_token: '{{Auth::user()->api_token}}',
            },
            methods: {
                updateSlug: function (val) {
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