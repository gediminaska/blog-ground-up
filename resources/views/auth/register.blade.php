@extends('layouts.app')


{{--<div class="container">--}}
    {{--<div class="row">--}}
        {{--<div class="col-md-8 col-md-offset-2">--}}
            {{--<div class="panel panel-default">--}}
                {{--<div class="panel-heading">Register</div>--}}

                {{--<div class="panel-body">--}}
                    {{--<form class="form-horizontal" method="POST" action="{{ route('register') }}">--}}
                        {{--{{ csrf_field() }}--}}

                        {{--<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">--}}
                            {{--<label for="name" class="col-md-4 control-label">Name</label>--}}

                            {{--<div class="col-md-6">--}}
                                {{--<input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>--}}

                                {{--@if ($errors->has('name'))--}}
                                    {{--<span class="help-block">--}}
                                        {{--<strong>{{ $errors->first('name') }}</strong>--}}
                                    {{--</span>--}}
                                {{--@endif--}}
                            {{--</div>--}}
                        {{--</div>--}}

                        {{--<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">--}}
                            {{--<label for="email" class="col-md-4 control-label">E-Mail Address</label>--}}

                            {{--<div class="col-md-6">--}}
                                {{--<input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>--}}

                                {{--@if ($errors->has('email'))--}}
                                    {{--<span class="help-block">--}}
                                        {{--<strong>{{ $errors->first('email') }}</strong>--}}
                                    {{--</span>--}}
                                {{--@endif--}}
                            {{--</div>--}}
                        {{--</div>--}}

                        {{--<div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">--}}
                            {{--<label for="password" class="col-md-4 control-label">Password</label>--}}

                            {{--<div class="col-md-6">--}}
                                {{--<input id="password" type="password" class="form-control" name="password" required>--}}

                                {{--@if ($errors->has('password'))--}}
                                    {{--<span class="help-block">--}}
                                        {{--<strong>{{ $errors->first('password') }}</strong>--}}
                                    {{--</span>--}}
                                {{--@endif--}}
                            {{--</div>--}}
                        {{--</div>--}}

                        {{--<div class="form-group">--}}
                            {{--<label for="password-confirm" class="col-md-4 control-label">Confirm Password</label>--}}

                            {{--<div class="col-md-6">--}}
                                {{--<input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>--}}
                            {{--</div>--}}
                        {{--</div>--}}

                        {{--<div class="form-group">--}}
                            {{--<div class="col-md-6 col-md-offset-4">--}}
                                {{--<button type="submit" class="btn btn-primary">--}}
                                    {{--Register--}}
                                {{--</button>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</form>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
{{--</div>--}}

@section('content')
    <div class="columns">
        <div class="column">
            <div class="card login-box">
                <div class="card-header title is-4">
                        Register
                </div>
                <div class="card-content">
                    <form class="form-horizontal" role="form" method="POST" action="{{ route('register') }}">
                        {{ csrf_field() }}

                        <div class="content">
                            <div class="control is-horizontal">
                                <div class="control-label">
                                    <label class="label">Name</label>
                                </div>
                                <div class="control is-fullwidth">
                                    <input name="name" class="input{{ $errors->has('name') ? ' is-danger' : '' }}" type="text" value="{{ old('name') }}" required autofocus>
                                </div>
                            </div>
                            @include('partials._form-errors', ['field' => 'name', 'type' => 'horizontal'])

                            <div class="control is-horizontal">
                                <div class="control-label">
                                    <label class="label">E-mail</label>
                                </div>
                                <div class="control is-fullwidth">
                                    <input name="email" class="input{{ $errors->has('email') ? ' is-danger' : '' }}" type="text" value="{{ old('email') }}" required>
                                </div>
                            </div>
                            @include('partials._form-errors', ['field' => 'email', 'type' => 'horizontal'])

                            <div class="control is-horizontal">
                                <div class="control-label">
                                    <label class="label">Password</label>
                                </div>
                                <div class="control is-fullwidth">
                                    <input name="password" class="input{{ $errors->has('password') ? ' is-danger' : '' }}" type="password" value="{{ old('password') }}" required>
                                </div>
                            </div>
                            @include('partials._form-errors', ['field' => 'password', 'type' => 'horizontal'])

                            <div class="control is-horizontal">
                                <div class="control-label">
                                    <label class="label">Confirm password</label>
                                </div>
                                <div class="control is-fullwidth">
                                    <input name="password_confirmation" class="input{{ $errors->has('password-confirm') ? ' is-danger' : '' }}" type="password" value="{{ old('password-confirm') }}" required>
                                </div>
                            </div>
                            @include('partials._form-errors', ['field' => 'password-confirm', 'type' => 'horizontal'])

                            <div class="control is-horizontal">
                                <div class="control-label">
                                    <!-- spacer -->
                                </div>
                                <div class="control">
                                    <button class="button is-primary is-fullwidth m-t-10">Register</button>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

