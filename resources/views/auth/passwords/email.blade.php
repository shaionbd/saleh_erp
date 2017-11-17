@extends('layouts.app')

@section('content')

<div class="container">

    <div class="login-container-wrapper clearfix">

        <div class="tab-content">

            <div class="tab-pane active">

                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif

                <form class="form-horizontal login-form" method="POST" action="{{ route('password.email') }}">

                    {{ csrf_field() }}

                    <div class="form-group relative{{ $errors->has('email') ? ' has-error' : '' }}">

                        <input class="form-control input-lg" id="login_username" name="email" placeholder="E-mail Address" value="{{ old('email') }}" required="" type="email" autofocus> <i class="fa fa-user"></i>

                        @if ($errors->has('email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif

                    </div>

                    <div class="form-group">
                        <button class="btn btn-success btn-lg btn-block" type="submit">Send Password Reset Link</button>
                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection
