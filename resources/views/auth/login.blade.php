@extends('layouts.app')


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

