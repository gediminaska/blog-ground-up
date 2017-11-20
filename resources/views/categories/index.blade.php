@extends ('layouts.app')

@section('content')
    <h1>Categories</h1>
        @foreach($categories as $category)
            <h3>{{$category->name}}</h3>
        @endforeach
@endsection