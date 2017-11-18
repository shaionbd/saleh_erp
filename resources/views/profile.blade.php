@extends('layouts.app')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">

    <h1>Profile<small></small></h1>

    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i></a></li>
        <li class="active">profile</li>
    </ol>

</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-6">
            <!-- show profile -->

        </div>
        <div class="col-md-6">
            <!-- Profile Form -->
            <div class="box box-success">

                <div class="box-header with-border text-center">

                    <h3 class="box-title">{{ $profile->name }} Profile</h3>

                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <button class="btn btn-box-tool" data-widget="edit" onclick="enableEdit();"><i class="fa fa-pencil"></i></button>
                    </div>

                </div>

                <div class="box-body">

                    <form method="POST" action="{{ route('user.updateProfile') }}">

                        {{ csrf_field() }}

                        <input type="hidden" name="user_id" value="{{ $profile->id }}">

                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" readonly="" name="name" class="form-control input-profile" value="{{ $profile->name }}">
                        </div>

                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" readonly="" name="email" class="form-control input-profile" value="{{ $profile->email }}">
                        </div>

                        <div class="form-group">
                            <label>Phone</label>
                            <input type="text" readonly="" name="phone" class="form-control input-profile" value="{{ $profile->phone }}">
                        </div>

                        <div class="form-group">
                            <label>Designation</label>
                            <input type="text" readonly="" class="form-control" value="{{ $profile->designation }}">
                        </div>

                        <div class="form-group">
                            <label>Supervisor</label>
                            <input type="text" readonly="" class="form-control" value="{{ $profile->supervisor }}">
                        </div>

                        <div class="form-group">
                            <label>Personal Website Link</label>
                            <input type="text" readonly="" name="website" class="form-control input-profile" value="{{ $profile->website }}">
                        </div>

                        <div class="form-group">
                            <label>About Me</label>
                            <textarea readonly="" name="about_me" class="form-control input-profile" rows="5">{{ $profile->about_me }}</textarea>
                        </div>

                        <div class="box-footer">
                            <button type="submit" class="btn btn-success">Update</button>
                        </div>

                    </form>

                </div>

            </div><!-- /.box -->
        </div>
    </div>

</section><!-- /.content -->

@endsection

@push('js')

    <script type="text/javascript">

        // function enableEdit() 
        // {
        //     $(".input-profile").removeAttr("readonly");
        // }
        (function(){
            $(".input-profile").removeAttr("readonly");
        })();

    </script>

@endpush