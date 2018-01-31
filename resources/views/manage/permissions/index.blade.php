@extends('layouts.manage')

@section('content')
    <div class="containter">
        <div class="columns">
            <div class="column">
                <h1 class="title">
                    Manage Permissions
                </h1>
            </div>
            <div class="column">
                <a href="{{route('permissions.create')}}" class="button is-primary"><i class="fa fa-user-add"></i>Create New Permission</a>
            </div>
        </div>
        <table class="table">
            <thead>
            <tr>
                <th>Name</th>
                <th>Slug</th>
                <th>Description</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($permissions as $permission)
            <tr>
                <th>{{ $permission->display_name }}</th>
                <td>{{ $permission->name }}</td>
                <td>{{ $permission->description }}</td>
                <td class="has-text-right"><a href="{{route('permissions.show', $permission->id)}}" class="button is-outlined is-small">View</a><a class="is-outlined is-small" href="{{route('permissions.edit', $permission->id)}}">Edit</a></td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection