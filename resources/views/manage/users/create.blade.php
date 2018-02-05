@extends('layouts.manage')

@section('content')

    <div class="flex-container" id="app">
        <div class="columns m-t-10">
            <div class="column">
                <h1 class="title">Create new user</h1>
            </div>
        </div>
        <hr class="m-t-0">
                <form action="{{route('users.store')}}" method="POST">
                    {{csrf_field()}}
                    <div class="columns">
                        <div class="column is-half">
                            <div class="field">
                                <label for="name" class="label">Name</label>
                                <p class="control">
                                    <input name="name" type="text" class="input" id="name">
                                </p>
                            </div>
                            <div class="field">
                                <label for="" class="label">Email:</label>
                                <p class="control">
                                    <input type="text" class="input" name="email" id="email">
                                </p>
                            </div>
                            <label for="password" class="label">Password</label>
                            <div class="field">
                                <b-radio native-value="auto" v-model="password_options" name="password_options">Generate new password</b-radio>
                            </div>
                            <div class="field">
                                <b-radio native-value="manual" v-model="password_options" name="password_options">Manually set new password</b-radio>
                                <p class="control">
                                    <input type="text" class="input" name="password" id="password" v-if="password_options == 'manual'" placeholder="Manually set password">
                                </p>
                            </div>
                        </div>
                        <div class="column is-half">
                            <label for="roles" class="label">Roles:</label>
                            <input type="hidden" class="input" name="roles" :value="rolesSelected">
                            <ul>
                                @foreach($roles as $role)

                                    <li class="field">
                                        <b-checkbox :native-value="{{$role->id}}" v-model="rolesSelected">{{$role->display_name}} ({{$role->description}})</b-checkbox>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <button class="button is-success m-t-30 is-fullwidth">Create User</button>
                </form>
    </div>

@endsection


@section('scripts')
    <script>
        var app = new Vue({
            el: '#app',
            data: {
                autoPassword: true,
                password_options: 'auto',
                rolesSelected: []
            },
            methods: {}
        });
    </script>
@endsection