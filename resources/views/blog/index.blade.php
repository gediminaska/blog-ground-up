@extends ('layouts.app')
@section('title', ' | Blog')

@section('content')
    @if(Auth::check())
        <p class="field">
            <a href="{{ route('posts.create') }}" class="button is-large is-primary is-outlined is-fullwidth" style="padding: 40px;">
            <span class="icon is-large">
                <i class="far fa-edit"></i>
            </span>
                <span>
                Create new post
            </span>
            </a>
        </p>
    @endif
    <div class="tabs is-centered">
        <ul>
            <li class="{{ Request::is('blog') ? 'is-active' : '' }}">
                <a href="{{ route('blog.index') }}">
                    <span class="icon is-small"><i class="fas fa-list-ul"></i></span>
                    <span>All posts</span>
                </a>
            </li>
            @foreach($categories as $category)
                <li class="{{ Request::is('blog/category/' . $category->id) ? 'is-active' : '' }}">
                    <a href="{{ route('blog.category', $category->id) }}">
                        <span class="icon is-small"><i class="{{$category->icon}}"></i></span>
                        <span>{{ $category->name }}</span>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
    @foreach($posts as $post)
                {{Html::linkRoute('blog.show', $post->title, $post->slug, ['class'=>'title is-4', 'style'=>'color:inherit'])}}<br>
                <small><span>Published  {{ $post->published_at->diffForHumans() }}, by    <span class="far fa-user m-l-5"></span> {{ $post->user->name }}, </span><span class="fas fa-comment m-l-5"> </span> <strong>{{ count($post->comments) }}</strong> comments</small>
                @if(Request::is('blog'))<p><strong>Category: </strong><span class="{{ $post->category->icon }}"></span> {{ $post->category->name }}</p>@else<br>@endif
                <br>
                <a href="{{ route('blog.show', $post->slug) }}"><img class= "index-thumbnail" src="{{ $post->image==null ? asset('images/no-image-landscape.png') : asset('images/' . $post->image) }}"></a>
                <h4 style="text-align: justify; white-space: pre-line">{{substr($post->body,0, 700)}} {{strlen($post->body)>700 ? "..." : ""}}</h4>
                <br>
                {{Html::linkRoute('blog.show', 'Show post', $post->slug, ['class'=>'button is-info is-outlined'])}}
                <br>
                <br>
        <hr>
    @endforeach
    {{ $posts->links() }}

@endsection

@section('panel-left')
    <div id="app-3">
        <h2 class="subtitle">Filer by tags:</h2>
        <div class="field">
            <b-checkbox v-model="checkbox">
                All
            </b-checkbox>
        </div>
        <div class="field" v-for="tag in tags">
            <b-checkbox v-model="selectedTags" native-value="tag">
                @{{ tag }}
            </b-checkbox>
        </div>
        @{{ selectedTags }}
        @{{ tags }}
        <p>{{ isset($filter) ? $filter : 'not set'}}</p>
    </div>
@stop

@section('scripts')
    <script>
        var app3 = new Vue({
            el: '#app-3',
            data: {
                tags: {!! $tags !!},
                selectedTags: [],
                checkboxGroup: ['Flint'],
                checkbox: true,
                checkboxCustom: 'Yes'
            },
            computed: {
                allTags: ()=>{
                   if(app3.selectedTags.length === 0) {
                       return true;
                   } else { return false;}
                }
            }

        })
    </script>
@stop


