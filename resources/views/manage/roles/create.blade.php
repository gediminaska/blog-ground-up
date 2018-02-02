@extends('layouts.manage')

@section('content')

    <div class="flex-container" id="app">
        <div class="columns m-t-10">
            <div class="column">
                <h1 class="title">Create new role</h1>
            </div>
            <div class="column">
                <a href="{{route('roles.index')}}" class="button is-primary is-pulled-right"><i class="fa fa-backward m-r-10"></i> Back to Roles</a>
            </div>
        </div>
        <hr class="m-t-0">
        <form action="{{route('roles.store')}}" method="POST">
            {{csrf_field()}}
            <div class="columns">
                <div class="column">
                    <div class="box">
                        <article class="media">
                            <div class="media-content">
                                <div class="content">
                                    <h1 class="title">Role Details</h1>
                                    <div class="field">
                                        <p class="control">
                                            <label for="display_name" class="label">Name (human readable)</label>
                                            <input type="text" class="input" name="display_name">
                                        </p>
                                    </div>
                                    <div class="field">
                                        <p class="control">
                                            <label for="name" class="label">Slug</label>
                                            <input type="text" class="input" name="name">
                                        </p>
                                    </div>
                                    <div class="field">
                                        <p class="control">
                                            <label for="display_name" class="label">Description</label>
                                            <input type="text" class="input" name="description">
                                        </p>
                                    </div>
                                    <input type="hidden" :value="permissionSelected" name="permissions">
                                </div>
                            </div>
                        </article>
                    </div>
                </div>
            </div>

            <div class="columns">
                <div class="column">
                    <div class="box">
                        <article class="media">
                            <div class="media-content">
                                <div class="content">
                                    <h2 class="title">Permissions</h2>
                                    @foreach($permissions as $permission)
                                        <div class="field">
                                            <b-checkbox v-model="permissionSelected" native-value="{{$permission->id}}">{{$permission->display_name}}  <em>({{$permission->description}})</em></b-checkbox>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </article>
                    </div>
                    <button type="submit" class="button is-primary">Create role</button>

                </div>
            </div>
        </form>
    </div>

@endsection

@section('scripts')
    <script>
        var app = new Vue({
            el: '#app',
            data: {
                permissionSelected: []
            }
        });
    </script>
@endsection
