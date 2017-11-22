@extends ('layouts.app')

@section('content')
    <h1>Posts</h1>
    @foreach($posts as $post)
        <div class="row">
            <div class="col-md-8">
                <h3>{{$post->title}}</h3>
                <h4>{{$post->body}}</h4>
                <p><strong>Category:</strong>{{ $post->category->name }}</p>
            </div>
            <div class="col-md-4">
                {{ Html::linkRoute('posts.show', 'View', [$post->id], ['class'=>'btn btn-secondary']) }}
                {{ Html::linkRoute('posts.edit', 'Edit', [$post->id], ['class'=>'btn btn-primary']) }}
                {{ Form::open(['route'=> ['posts.destroy', $post->id], 'method'=>'DELETE']) }}
                {{ Form::submit('Delete', ['class'=>'btn btn-danger btn-sm']) }}
                {{ Form::close() }}
            </div>
        </div>
    @endforeach
    {{ $posts->links() }}
@endsection