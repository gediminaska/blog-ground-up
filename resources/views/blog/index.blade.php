@extends ('layouts.app')

@section('content')
    <h1>Posts</h1>
    @foreach($posts as $post)
        <div class="row">
            <div class="col-md-8">
                <span style="font-weight: bold; font-size: xx-large">{{$post->title}}</span>
                <small> Has {{ count($post->comments) }} comments </small>
                <h4>{{$post->body}}</h4>
                <p><strong>Category:</strong>{{ $post->category->name }}</p>
            </div>
            <div class="col-md-4">
                {{ Html::linkRoute('blog.show', 'View', [$post->slug], ['class'=>'btn btn-secondary']) }}

            </div>
        </div>
    @endforeach
    {{ $posts->links() }}
@endsection