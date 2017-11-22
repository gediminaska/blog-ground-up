@extends ('layouts.app')

@section('content')

    <h1>Categories</h1>
    {{ Form::open(['action' => 'CategoriesController@store']) }}
    {{Form::label('name', 'Category name:')}}
    {{Form::text('name', null, ['class' => 'form-control'])}}
    {{ Form::submit('Create') }}

    {{Form::close()}}
        @foreach($categories as $category)
            <h3>{{ Html::linkRoute('categories.show', "'" . $category->name . "' , with " . count($category->posts) . " posts.", [$category->id], ['style'=>'text-decoration:none']) }}</h3>
            {{ Form::open(['route'=> ['categories.update', $category->id], 'method'=>'PUT']) }}

            {{ Form::text('name', $category->name,['class' => 'form-control', 'style'=>'width:auto; float:left']) }}
            {{ Form::submit('Update name', ['class'=>'btn btn-success', 'style'=>'float:left']) }}
            {{ Form::close() }}
            <br>
            <br>
                {{ Form::open(['action' => 'CategoriesController@destroy', 'method' => 'DELETE']) }}
                {{ Form::hidden('id', $category->id) }}
                {{ Form::submit('Delete category and all posts', ['class'=>'btn btn-danger']) }}

                {{Form::close()}}


            <hr>
        @endforeach
@endsection