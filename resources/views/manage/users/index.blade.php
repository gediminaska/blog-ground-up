@extends('layouts.manage')

@section('content')
    <div class="containter">
        <div class="columns">
            <div class="column">
                <h1 class="title">
                    Manage Users
                </h1>
            </div>
            <div class="column">
                <a href="{{route('users.create')}}" class="button is-primary"><i class="fa fa-user-add"></i>Create New User</a>
            </div>
        </div>
        <table class="table">
            <thead>
            <tr>
                <th>Id</th>
                <th>Name</th>
                <th>Email
                <th>Date Created</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
            <tr>
                <th>{{ $user->id }}</th>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->created_at }}</td>
                <td><a href="{{route('users.edit', $user->id)}}" class="button is-outlined">Edit</a></td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    {{$users->links()}}
@endsection