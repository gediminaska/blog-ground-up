@extends ('layouts.app')

@section('content')

    <h1 style="color">Categories</h1>
    {{ Form::open(['action' => 'CategoriesController@store']) }}
    {{Form::label('name', 'Category name:')}}
    {{Form::text('name', null, ['class' => 'form-control'])}}
    {{ Form::submit('Create') }}

    {{Form::close()}}
        @foreach($categories as $category)
            <h3>{{ Html::linkRoute('categories.show', $category->name, [$category->id], ['style'=>'text-decoration:none']) }}</h3>
                {{ Form::open(['action' => 'CategoriesController@destroy', 'method' => 'DELETE']) }}
                {{ Form::hidden('id', $category->id) }}
                {{ Form::submit('Delete') }}

                {{Form::close()}}
        @endforeach
@endsection