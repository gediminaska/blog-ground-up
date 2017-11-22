@extends ('layouts.app')

@section('content')

    <h1>Posts in categogry {{ $category->name }}</h1>
    {{ Form::open(['action' => 'CategoriesController@store']) }}
    {{Form::label('name', 'Category name:')}}
    {{Form::text('name', null, ['class' => 'form-control'])}}
    {{ Form::submit('Create') }}

    {{Form::close()}}
    @foreach($categories as $category)
        <h3>{{$category->name}}</h3>
        {{ Form::open(['action' => 'CategoriesController@destroy', 'method' => 'DELETE']) }}
        {{ Form::hidden('id', $category->id) }}
        {{ Form::submit('Delete') }}

        {{Form::close()}}
    @endforeach
@endsection