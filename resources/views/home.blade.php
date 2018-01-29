@extends('layouts.app')

@section('content')


            <div class="panel panel-default" style="margin:auto; margin-bottom:20px; max-width: 200px;">
                <div class="panel-heading" style="margin:auto; max-width: 200px;">Dashboard</div>

                <div class="panel-body" style="margin:auto">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    You are logged in!
                </div>
            </div>
                {{ Html::linkRoute('blog.index', 'Go to blog', [],['class'=>'jumbotron btn btn-block', 'style'=>'font-size: 52px; font-weight:bold; background-color: rgb(192, 214, 228)']) }}

                <h1 style="text-align: center; font-weight:bold">Your posts</h1>
                @foreach(Auth::user()->posts as $post)
                    <div class="row">
                        <div class="col-md-10">
                            <span style="font-weight: bold; font-size: x-large">{{$post->title}}</span>
                            <small> Has {{ $post->image==null ? '' : "an image and " }}{{count($post->comments) }} comments </small>
                            <h5>{{substr($post->body, 0, 300)}}{{ strlen($post->body)>300 ? "..." : ""}}</h5>
                            <p><strong>Category:</strong>{{ $post->category->name }}</p>
                        </div>
                        <div class="col-md-2">
                            {{ Html::linkRoute('posts.show', 'View', [$post->id], ['class'=>'btn btn-secondary btn-block']) }}
                            {{ Html::linkRoute('posts.edit', 'Edit', [$post->id], ['class'=>'btn btn-secondary btn-block']) }}
                            {{ Form::open(['route'=> ['posts.destroy', $post->id], 'method'=>'DELETE']) }}
                            {{ Form::submit('Delete', ['class'=>'btn btn-block', 'style'=>'color: #721c24; margin:auto']) }}
                            {{ Form::close() }}
                        </div>
                    </div>
                    <hr>
                @endforeach


@endsection
