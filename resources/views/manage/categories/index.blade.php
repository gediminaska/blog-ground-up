
@extends ('layouts.manage')

@section('content')

    <h1 class="title is-2">Categories</h1>
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
                <label class="label"><a href="https://fontawesome.com/icons?d=gallery&m=free">Fontawesome </a>icon:</label>
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
        <a href="{{ route('categories.show', $category->id) }}"><i class="{{ $category->icon }} fa-2x m-r-10 m-b-10" style="color: black"></i><span class="title is-3">{{ $category->name }} </span><span class="subtitle is-6">has {{$category->posts()->count()}} posts</span></a>
        {{ Form::open(['route'=> ['categories.update', $category->id], 'method'=>'PUT']) }}
        <div class="columns">

            <div class="column is-one-quarter">
                <div class="control-label">
                    <label class="label">Name:</label>
                </div>

                <div class="control">
                    <input name="name" value="{{ $category->name }}" class="input{{ $errors->has('name') ? ' is-danger' : '' }}" type="text" required>
                    @include('partials._form-errors', ['field' => 'name', 'type' => 'horizontal'])
                </div>
            </div>

            <div class="column is-one-quarter">
                <div class="control-label">
                    <label class="label">Icon:</label>
                </div>

                <input name="icon" value="{{ $category->icon }}" class="input{{ $errors->has('icon') ? ' is-danger' : '' }}" type="text" required>
                @include('partials._form-errors', ['field' => 'icon', 'type' => 'horizontal'])
            </div>

            <div class="column is-one-quarter">
                <button class="button is-primary m-t-25 is-fullwidth" type="submit"><i class="fas fa-edit m-r-10" > </i>Update</button>
            {{ Form::close() }}
            </div>

            <div class="column in-one-quarter">
                {{ Form::open(['route' => ['categories.destroy', $category->id], 'method' => 'DELETE']) }}
                <button class="button is-danger m-t-25 is-fullwidth" type="submit"><i class="far fa-trash-alt m-r-10" > </i>Delete with posts</button>
                {{Form::close()}}
            </div>
        </div>
        <hr>
    @endforeach
@endsection