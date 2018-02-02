@extends('layouts.manage')

@section('content')

    <div class="flex-container">
        <div class="columns m-t-10">
            <div class="column">
                <h1 class="title">{{$role->display_name}}<small class="m-l-25"><em>({{$role->name}})</em></small></h1>
                <h5>{{$role->description}}</h5>
            </div>
            <div class="column">
                <a href="{{route('roles.edit', $role->id)}}" class="button is-secondary is is-fullwidth"><i class="fa fa-edit m-r-10"></i>Edit</a>
            </div>
            <div class="column">
                <a href="{{route('roles.index')}}" class="button is-primary is-pulled-right is-fullwidth"><i class="fa fa-backward m-r-10"></i> Back to Roles</a>
            </div>
        </div>
        <hr class="m-t-0">
        <div class="columns">
            <div class="column">
                <div class="box">
                    <article class="media">
                        <div class="media-content">
                            <div class="content">
                                <h1 class="title">Permissions</h1>
                                <ul>
                                    @foreach($role->permissions as $r)
                                        <li>{{$r->display_name}} <em class="m-l-10">({{$r->description}})</em></li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </article>
                </div>
            </div>
        </div>
    </div>

@endsection

