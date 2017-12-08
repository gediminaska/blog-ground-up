@extends ('layouts.app')

@section('content')

    <h1 class="title is-3">Categories</h1>
    {{ Form::open(['action' => 'CategoriesController@store']) }}
    <div class="columns">

        <div class="column is-one-third">
            <div class="control-label">
                <label class="label">Name:</label>
            </div>
            <div class="control">
                <input name="name" class="input{{ $errors->has('name') ? ' is-danger' : '' }}" type="text" required>
                @include('partials._form-errors', ['field' => 'name', 'type' => 'horizontal'])
            </div>
        </div>
        <div class="column is-one-third">
            <div class="control-label">
                <label class="label">Icon:</label>
            </div>
                <input name="icon" class="input{{ $errors->has('icon') ? ' is-danger' : '' }}" type="text" required>
                @include('partials._form-errors', ['field' => 'icon', 'type' => 'horizontal'])
        </div>
        <div class="column is-one-third">
            <button class="button is-success m-t-25 is-fullwidth" type="submit"><i class="fas fa-plus-circle" > </i>Create new category</button>
        </div>
        {{Form::close()}}
    </div>
    <hr>
    @foreach($categories as $category)
        <h3>{{ Html::linkRoute('categories.show', "'" . $category->name . "' , with " . count($category->posts) . " posts.", [$category->id], ['class'=>'title is-5']) }}</h3>
        {{ Form::open(['route'=> ['categories.update', $category->id], 'method'=>'PUT']) }}
        <div class="columns">

            <div class="column is-one-quarter">
                <div class="control-label">
                    <label class="label">Name:</label>
                </div>

                <div class="control">
                    <input name="name" value={{ $category->name }} class="input{{ $errors->has('name') ? ' is-danger' : '' }}" type="text" required>
                    @include('partials._form-errors', ['field' => 'name', 'type' => 'horizontal'])
                </div>
            </div>

            <div class="column is-one-quarter">
                <div class="control-label">
                    <label class="label">Icon:</label>
                </div>

                <input name="icon" value={{ $category->icon }} class="input{{ $errors->has('icon') ? ' is-danger' : '' }}" type="text" required>
                @include('partials._form-errors', ['field' => 'icon', 'type' => 'horizontal'])
            </div>

            <div class="column is-one-quarter">
                <button class="button is-primary m-t-25 is-fullwidth" type="submit"><i class="fas fa-edit m-r-10" > </i>Update</button>
            {{ Form::close() }}
            </div>

            <div class="column in-one-quarter">
                {{ Form::open(['action' => 'CategoriesController@destroy', 'method' => 'DELETE']) }}
                {{ Form::hidden('id', $category->id) }}
                <button class="button is-danger m-t-25 is-fullwidth" type="submit"><i class="far fa-trash-alt m-r-10" > </i>Delete with posts</button>
                {{Form::close()}}
            </div>
        </div>
        <hr>
    @endforeach
@endsection