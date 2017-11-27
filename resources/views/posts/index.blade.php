@extends ('layouts.app')

@section('content')
    <h1>Your posts</h1>
    @foreach(Auth::user()->posts as $post)
        <div class="row">
            <div class="col-md-8">
                <span style="font-weight: bold; font-size: xx-large">{{$post->title}}</span>
                <small> Has {{ count($post->comments) }} comments </small>
                <h4>{{substr($post->body, 0, 300)}}{{ strlen($post->body)>300 ? "..." : ""}}</h4>
                <p><strong>Category:</strong>{{ $post->category->name }}</p>
            </div>
            <div class="col-md-4">
                {{ Html::linkRoute('posts.show', 'View', [$post->id], ['class'=>'btn btn-secondary']) }}
                {{ Html::linkRoute('posts.edit', 'Edit', [$post->id], ['class'=>'btn btn-secondary']) }}
                {{ Form::open(['route'=> ['posts.destroy', $post->id], 'method'=>'DELETE']) }}
                {{ Form::submit('Delete', ['class'=>'button', 'style'=>'color: #721c24']) }}
                {{ Form::close() }}
            </div>
        </div>
    @endforeach

@endsection