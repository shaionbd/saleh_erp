@extends('layouts.app')

@section('content')

<div class="container">

    <div class="login-container-wrapper clearfix">

        <div class="tab-content">
            
            <div class="tab-pane active">

                <form class="form-horizontal login-form" method="POST" action="{{ route('password.request') }}">

                    {{ csrf_field() }}

                    <div class="form-group relative{{ $errors->has('email') ? ' has-error' : '' }}">

                        <input class="form-control input-lg" id="login_username" name="email" value="{{ old('email') }}" placeholder="E-mail Address" required="" type="email"> <i class="fa fa-user"></i>
                        
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
                        <button class="btn btn-success btn-lg btn-block" type="submit">Reset Password</button>
                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection
