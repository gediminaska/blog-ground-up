@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Login</div>

                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ route('login') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">Password</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Login
                                </button>

                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                    Forgot Your Password?
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


@section('content')
    <div class="columns">
        <div class="column is-one-third is-offset-one-third">
            <h1 class="title ">Login</h1>

            <form class="form-horizontal" role="form" method="POST" action="{{ route('login') }}">
                {{ csrf_field() }}

                <div class="login-form">
                    <p class="control has-icon has-icon-right">
                        <input id="email" type="email" class="input email-input m-b-10" name="email" type="text" placeholder="email@gmail.com" value="{{ old('email') }}" required autofocus>

                        <span class="icon user">
							<i class="fa fa-user"></i>
						</span>

                        @if ($errors->has('email'))
                            <span class="help-block">
								<strong>{{ $errors->first('email') }}</strong>
							</span>
                        @endif
                    </p>

                    <p class="control has-icon has-icon-right m-b-10">
                        <input id="password" type="password" class="input password-input" type="password" placeholder="password" name="password" required>

                        <span class="icon user">
							<i class="fa fa-lock"></i>
						</span>

                        @if ($errors->has('password'))
                            <span class="help-block">
								<strong>{{ $errors->first('password') }}</strong>
							</span>
                        @endif
                    </p>

                    <div class="field">
                        <div class="control">
                            <label class="checkbox">
                                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
                            </label>
                        </div>
                    </div>

                    <p class="control login">
                        <button class="button is-success is-outlined is-fullwidth" type="submit">Login</button>
                    </p>
                </div>

                    <p class="has-text-centered m-t-10">
                        <a class="btn btn-link" href="{{ route('password.request') }}">Forgot password</a>
                    </p>
            </form>
        </div>
    </div>
@endsection

@endsection