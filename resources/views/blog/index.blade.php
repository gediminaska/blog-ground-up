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
            <li class="{{ Request::is('blog') || Request::is('blog/filter'.'*') ? 'is-active' : '' }}">
                <a href="{{ route('blog.index')  }}">
                    <span class="icon is-small"><i class="fas fa-list-ul"></i></span>
                    <span>All posts</span>
                </a>
            </li>
            @foreach($categories as $category)
                <li class="{{ Request::is('blog/category/' . $category->id .'*') ? 'is-active' : '' }}">
                    <a href="{{ route('blog.category', $category->id) }}">
                        <span class="icon is-small"><i class="{{$category->icon}}"></i></span>
                        <span>{{ $category->name }}</span>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
    <div id="app-4" class="is-pulled-right" >
        <b-dropdown >
            <button class="button is-secondary is-small" slot="trigger">
                <span>Filter by tags</span>
                <b-icon icon="menu-down"></b-icon>
            </button>

            <form v-cloak action="{{ Request::is('blog/category'.'*') ? route('blog.category.filtered', [request('category_id'), 'filter']) : route('blog.index.filtered', 'filter') }}" method="get">
                <label for="" class="checkbox filter-checkbox" v-for="tag in tagsList">
                    <input type="checkbox" name='filter[]' :value="tag.name" :checked="tag.checked">
                    @{{ tag.name }}
                    <br>
                </label>
                <input class="button is-small" type="submit" value="Filter">
            </form>
        </b-dropdown>
    </div>
    @foreach($posts as $post)
                {{Html::linkRoute('blog.show', $post->title, $post->slug, ['class'=>'title is-4', 'style'=>'color:inherit'])}}<br>
                <small><span>Published  {{ $post->published_at->diffForHumans() }}, by    <span class="far fa-user m-l-5"></span> {{ $post->user->name }}, </span><span class="fas fa-comment m-l-5"> </span> <strong>{{ count($post->comments) }}</strong> comments</small>
                @foreach($post->tags as $tag)
                    <span class="tag">{{ $tag->name }}</span>
                @endforeach
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



@section('scripts')
    <script>
        var app4 = new Vue({
            el: '#app-4',
            data: {
                tagsList: [],
            },
            computed: {
            },
            methods: {
                populateTagsList() {
                    let a = {!! $tags !!};
                    for(let i = 0; i < a.length; i++){
                        let obj = {};
                        obj.name = a[i];
                        obj.checked = {!! collect(request('filter')) !!}.includes(a[i]);
                        this.tagsList.push(obj);
                    }
                },
            },
            mounted() {
                this.populateTagsList();
            }
        });
    </script>
@stop


