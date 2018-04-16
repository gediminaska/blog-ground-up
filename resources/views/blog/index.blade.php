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
    <div class="aa-input-container is-pulled-right" id="aa-input-container">
        <input type="search" id="aa-search-input" class="aa-input-search" placeholder="Search for posts..." name="search" autocomplete="true" />
    </div>
    <div id="app-4">
        {{--<ais-index--}}
                {{--app-id="{{ config('scout.algolia.id') }}"--}}
                {{--api-key="{{ config('scout.algolia.key') }}"--}}
                {{--index-name="posts"--}}
        {{-->--}}
            {{--<ais-search-box></ais-search-box>--}}
            {{--<ais-results style="position: absolute; background-color: white; padding: 5px; border: #00c4a7">--}}
                {{--<template slot-scope="{ result }">--}}
                    {{--<h2>--}}
                        {{--<a href={{ Request::url() }}>--}}
                            {{--@{{ result.title }}--}}
                        {{--</a>--}}
                    {{--</h2>--}}
                    {{--<p> @{{ result._snippetResult.body.value }} </p>--}}
                    {{--<hr style="margin: 0px">--}}

                {{--</template>--}}
            {{--</ais-results>--}}
        {{--</ais-index>--}}
        <b-dropdown  class="is-pulled-right" >
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

    @if(count($posts) > 0)
        @foreach($posts as $post)
            {{Html::linkRoute('blog.show', $post->title, $post->slug, ['class'=>'title is-4', 'style'=>'color:inherit'])}}<br>
            <small><span>Published  {{ $post->published_at ? $post->published_at->diffForHumans() : 'is not' }}, by    <span class="far fa-user m-l-5"></span> {{ $post->user->name }}, </span><span class="fas fa-comment m-l-5"> </span> <strong>{{ count($post->comments) }}</strong> comments</small>
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
        <p>{{ count($posts) }}</p>
    @endif
    {{ $posts->links() }}

@endsection



@section('scripts')
    <script src="https://cdn.jsdelivr.net/algoliasearch/3/algoliasearch.min.js"></script>
    <script src="https://cdn.jsdelivr.net/autocomplete.js/0/autocomplete.min.js"></script>
    <script>
        var client = algoliasearch('PI8U7T64NH', 'c7fc6f0970b13edfa72f6b6a3d09df3f');
        var index = client.initIndex('posts');
        //initialize autocomplete on search input (ID selector must match)
        autocomplete('#aa-search-input',
            { hint: true, debug: true }, {
                source: autocomplete.sources.hits(index, {hitsPerPage: 5}),
                //value to be displayed in input control after user's suggestion selection
                displayKey: 'title',
                //hash of templates used when rendering dataset
                templates: {
                    //'suggestion' templating function used to render a single suggestion
                    suggestion: function(suggestion) {
                        return '<a href="blog/' + suggestion.slug + '"><span><strong>' +
                            suggestion._highlightResult.title.value + '</strong></a></span><br><span>' +
                            suggestion._snippetResult.body.value + '</span>';
                    }
                }
            });
    </script>


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


