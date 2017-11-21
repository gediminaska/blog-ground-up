@extends ('layouts.app')

@section('content')
    <h1>View post</h1>
    <h3>{{$post->title}}</h3>
    <h4>{{$post->body}}</h4>
@endsection