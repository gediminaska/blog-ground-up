@extends ('layouts.app')

@section('content')
    @if(Auth::check())
        {{ Html::linkRoute('posts.create', "Create new post", [], ['class'=>'btn btn-block btn-primary btn-lg', 'style'=>'background-color:#C0D6E4; color:inherit; border-color:#eee']) }}
    @endif
    <h1 style="text-align: center; font-weight:bold">Posts</h1>
    @foreach($posts as $post)
                {{Html::linkRoute('blog.show', $post->title, $post->slug, ['style'=>'font-weight: bold; font-size: x-large; color:inherit'])}}
                <small><span>Published  {{ $post->created_at->diffForHumans() }}, by    <span class="fa fa-user-o m-l-5"></span> {{ $post->user->name }}, </span><span class="fa fa-comment m-l-5"> </span> <strong>{{ count($post->comments) }}</strong> comments</small>
                <p><strong>Category:</strong>{{ $post->category->name }}</p>
                <img class= "index-thumbnail" src="{{ $post->image==null ? asset('images/no-image-landscape.png') : asset('images/' . $post->image) }}">
                <h4 style="text-align: justify">{{substr($post->body,0, 700)}} {{strlen($post->body)>700 ? "..." : ""}}</h4>
                <br>
                {{Html::linkRoute('blog.show', 'Show post', $post->slug, ['class'=>'button', 'style'=>'background-color:#eee; color:inherit; border-color:rgb(200, 238, 255); float:left'])}}
                <br>
                <br>
        <hr>
    @endforeach
    {{ $posts->links() }}

@endsection