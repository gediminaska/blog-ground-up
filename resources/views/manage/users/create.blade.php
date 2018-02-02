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
            <div class="field">
                <label for="name" class="label">Name</label>
                <p class="control">
                    <input type="text" class="input" id="name" name="name">
                </p>
            </div>
            <div class="field">
                <label for="" class="label">Email:</label>
                <p class="control">
                <input type="text" class="input" name="email" id="email">
                </p>
            </div>
            <div class="field">
                <label for="password" class="label">Password</label>
                <p class="control">
                    <input type="password" class="input" name="password" id="password" :disabled="autoPassword" placeholder="Manually add a password">
                    <b-checkbox class="m-t-15" name="auto_generate" v-model="autoPassword">Auto Generate Password</b-checkbox>
                </p>

            </div>
            <button class="button is-success">Create User</button>
        </form>
    </div>

@endsection

@section('scripts')
    <script>
        var app = new Vue({
            el: '#app',
            data: {
                autoPassword: true,
            },
            methods: {}
        });
    </script>
@endsection
