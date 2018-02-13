@extends('layouts.manage')

@section('content')
    <div class="flex-container">
        <div class="columns m-t-10">
            <div class="column">
                <h1 class="title">Manage Roles</h1>
            </div>
            <div class="column">
                <a href="{{route('roles.create')}}" class="button is-primary is-pulled-right"><i class="fa fa-user-plus m-r-10"></i> Create New Role</a>
            </div>
        </div>
        <hr class="m-t-0">

        <div class="columns is-multiline">
            @foreach ($roles as $role)
                <div class="column is-one-quarter role-card">
                    <div class="box">
                        <article class="media">
                            <div class="media-content">
                                <div class="content">
                                    <h3 class="title">{{$role->display_name}}</h3>
                                    <h4 class="subtitle"><em>{{$role->name}}</em></h4>
                                    <p>
                                        {{$role->description}}
                                    </p>
                                    @if($role->hasPermission('create-users'))
                                        <span  title="Manage users"><i class="fas fa-user-plus"></i></span>
                                    @endif

                                    @if($role->hasPermission('create-category'))
                                        <span title="Manage categories"><i class="fas fa-list-ul"></i></span>
                                    @endif

                                    @if($role->hasPermission('create-post'))
                                        <span title="Manage posts"><i class="fas fa-file-alt"></i></span>
                                    @endif

                                    @if($role->hasPermission('publish-post'))
                                        <span title="Publish posts"><i class="fas fa-bullhorn"></i></span>
                                    @endif

                                    @if($role->hasPermission('create-roles'))
                                        <span title="Manage roles"><i class="fas fa-id-badge"></i></span>
                                    @endif

                                    @if($role->hasPermission('create-permission'))
                                        <span title="Manage permissions"><i class="fas fa-ban"></i></span>
                                    @endif
                                </div>

                                <div class="columns is-mobile">
                                    <div class="column is-one-half">
                                        <a href="{{route('roles.show', $role->id)}}" class="button is-primary is-fullwidth">Details</a>
                                    </div>
                                    <div class="column is-one-half">
                                        <a href="{{route('roles.edit', $role->id)}}" class="button is-light is-fullwidth">Edit</a>
                                    </div>
                                </div>
                            </div>
                        </article>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection