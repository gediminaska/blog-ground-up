@extends ('layouts.manage')

@section('content')

    <h1 class="title">Posts in category <strong>{{ $category->name }}:</strong></h1>
    @foreach($category->posts as $post)
        <h2 class="title">{{ $post->title }}</h2>
        <h4 class="subtitle">{{ substr($post->body, 0, 200) . '...'}}</h4>
        <hr>
    @endforeach
    {{ Html::linkRoute('categories.index', 'Back to categories', [], ['class'=>'btn btn-secondary']) }}

@endsection