@extends ('layouts.app')

@section('content')

    <h1>Posts in category {{ $category->name }}</h1>
    @foreach($category->posts as $post)
        <h2>{{ $post->title }}</h2>
        <h4>{{ $post->body }}</h4>
    @endforeach
    {{ Html::linkRoute('categories.index', 'Back to categories', [], ['class'=>'btn btn-secondary']) }}

@endsection