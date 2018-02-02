@extends('layouts.manage')

@section('content')

    <div class="flex-container">
        <div class="columns m-t-10">
            <div class="column">
                <h1 class="title">{{$permission->display_name}}</h1>
                <h4 class="title">View permission details</h4>
            </div>
        </div>
            <div class="columns">
                <div class="column">
                    <a href="{{route('permissions.index')}}" class="button is-primary"><i class="fa fa-backward m-r-10"></i> Back to Permissions</a>
                    <a href="{{route('permissions.edit', $permission->id)}}" class="button is-secondary"><i class="fa fa-edit m-r-10"></i>Edit</a>
                </div>
            </div>
        <hr class="m-t-0">
        <div class="columns">
            <div class="column">
                <div class="field">
                    <label class="label">Name</label>
                    <pre>{{$permission->display_name}}</pre>
                </div>
                <div class="field">
                    <label class="label">Description</label>
                    <pre>{{$permission->description}}</pre>
                </div>
            </div>
        </div>
    </div>

@endsection

