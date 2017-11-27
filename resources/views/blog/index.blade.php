@extends ('layouts.app')

@section('content')
    <h1>Posts</h1>
    @foreach($posts as $post)

                {{Html::linkRoute('blog.show', $post->title, $post->slug, ['style'=>'font-weight: bold; font-size: x-large; color:inherit'])}}
                <small>Published  {{ $post->created_at->diffForHumans() }}, by {{ $post->user->name }}, {{ count($post->comments) }} comments </small>
                <h4>{{substr($post->body,0, 600)}} {{strlen($post->body)>600 ? "..." : ""}}</h4>

                <p><strong>Category:</strong>{{ $post->category->name }}</p>
                {{Html::linkRoute('blog.show', 'Show post', $post->slug, ['class'=>'btn btn-primary btn-block', 'style'=>'background-color:#eee; color:inherit; border-color:#eee'])}}
                <br>
    @endforeach
    {{ $posts->links() }}

@endsection