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
            <div class="box box-info">
                <div class="box-body">
                    <div class="profile-container">
                        <div class="profile-header">
                            <h1>{{ $profile->name }}<h1>
                            <h2>{{ $profile->designation }}</h2>
                            <img src="storage/app/public/profile/{{ $profile->image}}" class="profile">
                        </div>
                        <div class="profile-content">
                            <h3>About Me</h3>
                            <p>{{ $profile->about_me }}</p>
                            
                            <h3>Professional Skills</h3>
                            <p>
                                @php
                                    $skills = explode(',', $profile->skills);
                                    for($i = 0; $i < sizeof($skills); $i++){
                                        echo '<span class="btn btn-success">'.trim($skills[$i]).'</span> ';
                                    }
                                @endphp
                            </p>
                            
                            <h3>Basic Information</h3>
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <table class="table table-bordered">
                                        <tbody>
                                            <tr>
                                                <td>Phone</td>
                                                <td>{{ $profile->phone }}</td>
                                            </tr>
                                            <tr>
                                                <td>Email</td>
                                                <td>{{ $profile->email }}</td>
                                            </tr>
                                            <tr>
                                                <td>Address</td>
                                                <td>{{ $profile->address }}</td>
                                            </tr>
                                            @if($profile->website)
                                            <tr>
                                                <td>Website</td>
                                                <td><a href="{{ $profile->website }}" target="_blank">{{ $profile->website }}</a></td>
                                            </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <h3>Experience</h3>
                            <p>{{ $profile->experience }}</p>

                            <h3>Social Link</h3>
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="social">
                                        <a title="twitter" href="{{ $profile->twitter_link }}" target="_blank"><i id="twitter" class="fa fa-twitter"></i></a>
                                        <a title="github" href="{{ $profile->github_link }}" target="_blank"><i id="github" class="fa fa-github"></i></a>
                                        <a title="linkedin" href="{{ $profile->linkedin_link }}" target="_blank"><i id="linkedin" class="fa fa-linkedin-square"></i></a>
                                        <a title="plus" href="{{ $profile->google_plus_link }}" target="_blank"><i id="plus" class="fa fa-google-plus"></i></a>
                                        <a title="facebook" href="{{ $profile->fb_link }}" target="_blank"><i id="facebook" class="fa fa-facebook"></i></a>
                                    </div>
                                </div>
                            </div>
                            <br><br>
                        </div>    
                    </div>
                </div>
            </div>
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

                    <form method="POST" action="{{ route('user.updateProfile') }}" enctype="multipart/form-data">

                        {{ csrf_field() }}

                        <input type="hidden" name="user_id" value="{{ $profile->id }}">

                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" readonly="" name="name" class="form-control input-profile" value="{{ $profile->name }}">
                        </div>

                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" readonly="" name="email" class="form-control" value="{{ $profile->email }}">
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
                            @php
                                $supervisor = App\User::find($profile->supervisor);
                            @endphp
                            <label>Supervisor</label>
                            <input type="text" readonly="" class="form-control" value="{{ $supervisor->name }}">
                        </div>

                        <div class="form-group">
                            <label>Personal Website Link</label>
                            <input type="text" readonly="" name="website" class="form-control input-profile" value="{{ $profile->website }}">
                        </div>

                        <div class="form-group">
                            <label>About Me</label>
                            <textarea readonly="" name="about_me" class="form-control input-profile" rows="5">{{ $profile->about_me }}</textarea>
                        </div>
                        
                        <div class="form-group">
                            <label>Skills <span class="text-danger">*add multiple skills with comma separator</span></label>
                            <input type="text" readonly="" name="skills" class="form-control input-profile" value="{{ $profile->skills }}">
                        </div>

                        <div class="form-group">
                            <label>Experience</label>
                            <input type="text" readonly="" name="experience" class="form-control input-profile" value="{{ $profile->experience }}">
                        </div>

                        <div class="form-group">
                            <label>Address</label>
                            <input type="text" readonly="" name="address" class="form-control input-profile" value="{{ $profile->address }}">
                        </div>

                        <div class="form-group">
                            <label>Twitter</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-twitter text-primary"></i></span>
                                <input type="text" class="form-control input-profile" readonly="" name="twitter_link" value="{{ $profile->twitter_link }}" placeholder="https://twitter.com/testname">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Github</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-github text-gray-dark"></i></span>
                                <input type="text" class="form-control input-profile" readonly="" name="github_link" value="{{ $profile->github_link }}" placeholder="https://github.com/testname">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Linkedin</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-linkedin text-primary"></i></span>
                                <input type="text" class="form-control input-profile" readonly="" name="linkedin_link" value="{{ $profile->linkedin_link }}" placeholder="https://www.linkedin.com/in/test-0138u2/">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Google Plus</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-google-plus text-danger"></i></span>
                                <input type="text" class="form-control input-profile" readonly="" name="google_plus_link" value="{{ $profile->goole_plus_link }}" placeholder="https://plus.google.com/u/0/xxxx">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Facebook</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-facebook text-primary"></i></span>
                                <input type="text" class="form-control input-profile" readonly="" name="fb_link" value="{{ $profile->fb_link }}" placeholder="https://facebook.com/testname">
                            </div>
                        </div>

                        <!-- image -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Image</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-user-o text-danger"></i></span>
                                        <input type="file" class="form-control input-profile" readonly="" name="image" id="image-input" accept="image/*">
                                    </div>
                                    <span id="image-error" class="text-danger hidden">*only image file is allowed</span>
                                </div>
                                <div class="form-group col-md-4 col-sm-6 col-md-offset-4">
                                    <center>
                                        <img id="image" src="storage/app/public/profile/{{ $profile->image }}" height="200" width="200" alt="">
                                    </center>
                                </div>
                            </div>
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