@extends('layouts.app')

@section('content')

<div class="container">

    <div class="login-container-wrapper clearfix">

        <ul class="switcher clearfix">

            <li class="first logo active" data-tab="login">                 
                <a>Login</a>
            </li>
            <li class="second logo" data-tab="sign_up">
                <a>Sign Up</a>
            </li>

        </ul>

        <div class="tab-content">

            <div class="tab-pane active" id="login">

                <form class="form-horizontal login-form" method="POST" action="{{ route('login') }}">

                    {{ csrf_field() }}

                    <div class="form-group relative{{ $errors->has('email') ? ' has-error' : '' }}">

                        <input class="form-control input-lg" id="login_username" name="email" placeholder="E-mail Address" value="{{ old('email') }}" required="" type="email" autofocus> <i class="fa fa-user"></i>

                        @if ($errors->has('email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif

                    </div>

                    <div class="form-group relative{{ $errors->has('password') ? ' has-error' : '' }}">

                        <input class="form-control input-lg" id="login_password" name="password" placeholder="Password" required="" type="password"> <i class="fa fa-lock"></i>
                        
                        @if ($errors->has('password'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif

                    </div>

                    <div class="form-group">
                        <button class="btn btn-success btn-lg btn-block" type="submit">Login</button>
                    </div>

                    <div class="checkbox checkbox-success">
                        <input id="stay-sign" type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label for="stay-sign">Stay signed in</label>
                    </div>

                    <hr/>

                    <div class="text-center">
                        <label><a href="{{ route('password.request') }}">Forgot your password?</a></label>
                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection
