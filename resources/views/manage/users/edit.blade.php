@extends('layouts.manage')

@section('content')

    <div class="flex-container" id="app">
        <div class="columns m-t-10">
            <div class="column">
                <h1 class="title">{{$user->name}}</h1>
                <h4 class="title">Edit user</h4>
            </div>
        </div>
        <hr class="m-t-0">
        <form action="{{route('users.update', $user->id)}}" method="POST">
            {{method_field('PUT')}}
            {{csrf_field()}}
            <div class="field">
                <label for="name" class="label">Name</label>
                <p class="control">
                    <input name="name" type="text" class="input" id="name" value="{{$user->name}}">
                </p>
            </div>
            <div class="field">
                <label for="" class="label">Email:</label>
                <p class="control">
                <input type="text" class="input" value="{{$user->email}}" name="email" id="email">
                </p>
            </div>
                <label for="password" class="label">Password</label>
                    <div class="field">
                        <b-radio native-value="keep" v-model="password_options" name="password_options">Do not change password</b-radio>
                    </div>
                    <div class="field">
                        <b-radio native-value="auto" v-model="password_options" name="password_options">Generate new password</b-radio>
                    </div>
                    <div class="field">
                        <b-radio native-value="manual" v-model="password_options" name="password_options">Manually set new password</b-radio>
                        <p class="control">
                            <input type="text" class="input" name="password" id="password" v-if="password_options == 'manual'" placeholder="Manually set password">
                        </p>
                    </div>

            <button class="button is-primary">Edit User</button>
        </form>
    </div>

@endsection

@section('scripts')
    <script>
        var app = new Vue({
            el: '#app',
            data: {
                password_options: 'keep',
            },
            methods: {}
        });
    </script>
@endsection
