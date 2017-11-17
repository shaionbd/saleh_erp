@extends('layouts.app')

@section('content')

<div class="container">

    <div class="login-container-wrapper clearfix">

        <ul class="switcher clearfix">

            <li class="first logo" data-tab="login">                 
                <a>Login</a>
            </li>
            <li class="second logo active" data-tab="sign_up">
                <a>Sign Up</a>
            </li>

        </ul>

        <div class="tab-content">
            
            <div class="tab-pane active" id="sign_up">

                <form class="form-horizontal login-form" method="POST" action="{{ route('register') }}">

                    {{ csrf_field() }}

                    <div class="form-group relative{{ $errors->has('name') ? ' has-error' : '' }}">

                        <input class="form-control input-lg" id="login_username" placeholder="Name" name="name" value="{{ old('name') }}" required="" type="text" autofocus> <i class="fa fa-user"></i>
                        
                        @if ($errors->has('name'))
                            <span class="help-block">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                        @endif

                    </div>

                    <div class="form-group relative{{ $errors->has('email') ? ' has-error' : '' }}">

                        <input class="form-control input-lg" id="login_user_email" name="email" value="{{ old('email') }}" placeholder="E-mail Address" required="" type="email"> <i class="fa fa-user"></i>
                        
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

                    <div class="form-group relative{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">

                        <input class="form-control input-lg" id="login_password" name="password_confirmation" placeholder="Repeat Password" required="" type="password"> <i class="fa fa-lock"></i>

                        @if ($errors->has('password_confirmation'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password_confirmation') }}</strong>
                            </span>
                        @endif

                    </div>

                    <div class="form-group">
                        <button class="btn btn-success btn-lg btn-block" type="submit">Sign Up</button>
                    </div>

                    <hr>

                    <div class="text-center">
                        <label><a href="#">Already Member?</a></label>
                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection
