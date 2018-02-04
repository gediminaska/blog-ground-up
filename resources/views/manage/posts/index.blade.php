@extends ('layouts.manage')

@section('content')
    <h1 class="title is-2">Your posts</h1>
    @foreach(Auth::user()->posts as $post)
        <div class="columns">
            <div class="column is-two-thirds">
                <span class="title is-5"><a href="{{ route('posts.show', $post->id) }}" style="color: inherit">{{$post->title}}</a></span>
                <small> Has {{ count($post->comments) }} comments </small>
                <h4>{{substr($post->body, 0, 300)}}{{ strlen($post->body)>300 ? "..." : ""}}</h4>
                <p><strong>Category: </strong> {{ $post->category->name }}</p>
            </div>
            <div class="column is-one-third">
                <div class="columns">
                    <div class="column">
                        {{ Html::linkRoute('posts.show', 'View', [$post->id], ['class'=>'button is-secondary is-fullwidth']) }}
                    </div>
                </div>
                <div class="columns">
                    <div class="column is-half">
                        {{ Html::linkRoute('posts.edit', 'Edit', [$post->id], ['class'=>'button is-primary is-fullwidth']) }}
                    </div>
                    <div class="column is-half">
                        {{ Form::open(['route'=> ['posts.destroy', $post->id], 'method'=>'DELETE']) }}
                        {{ Form::submit('Delete', ['class'=>'button is-danger is-fullwidth', 'style'=>'color: #721c24']) }}
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    @endforeach

@endsection
