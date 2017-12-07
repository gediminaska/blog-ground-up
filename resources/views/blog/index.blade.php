@extends ('layouts.app')

@section('content')
    @if(Auth::check())
        <p class="field">
            <a href="{{ route('posts.create') }}" class="button is-large is-primary is-outlined is-fullwidth" style="padding: 40px;">
            <span class="icon is-large">
                <i class="fa fa-pencil-square-o"></i>
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
                    <span class="icon is-small"><i class="fa fa-image"></i></span>
                    <span>All posts</span>
                </a>
            </li>
            @foreach($categories as $category)
                <li class="{{ Request::is('blog/category/' . $category->id) ? 'is-active' : '' }}">
                    <a href="{{ route('blog.category', $category->id) }}">
                        <span class="icon is-small"><i class="fa fa-music"></i></span>
                        <span>{{ $category->name }}</span>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
    @foreach($posts as $post)
                {{Html::linkRoute('blog.show', $post->title, $post->slug, ['class'=>'title is-4', 'style'=>'color:inherit'])}}<br>
                <small><span>Published  {{ $post->created_at->diffForHumans() }}, by    <span class="fa fa-user-o m-l-5"></span> {{ $post->user->name }}, </span><span class="fa fa-comment m-l-5"> </span> <strong>{{ count($post->comments) }}</strong> comments</small>
                <p><strong>Category: </strong>{{ $post->category->name }}</p>
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