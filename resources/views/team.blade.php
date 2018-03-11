@extends('layouts.app')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">

    <h1>Team<small></small></h1>

    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i></a></li>
        <li class="active">my team</li>
    </ol>

</section>

<!-- Main content -->
<section class="content">
    
    <div class="box box-info">

        <div class="box-header with-border">
            <h3 class="box-title">My Team</h3>
        </div><!-- /.box-header -->

        <div class="box-body">

            <table class="table table-bordered">

                <thead>

                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Designation</th>
                        <th>Experience</th>
                        <th>Availablity</th>
                        <th class="text-center">Action</th>

                    </tr>

                </thead>

                <tbody>

                    @foreach($team_members as $team_member)
                    
                        <tr>

                            <td>{{ $team_member->name }}</td>
                            <td>{{ $team_member->email }}</td>
                            <td>{{ $team_member->phone }}</td>
                            <td>{{ $team_member->designation }}</td>
                            <td>{{ $team_member->experience }}</td>
                            <?php if($team_member->is_available == 1): ?>
                                <td style="color: green;">Yes</td>
                            <?php else: ?>
                                <td style="color: red;">No</td>
                            <?php endif ?>
                            <td><a href="#myModal" data-toggle="modal" onclick="edit_manager_team( {{ $team_member->id }} );" class="btn btn-success"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a></td>
                            <td><a href="{{ route('user.managerDeleteTeam',  $team_member->id) }}" class="btn btn-danger"><i class="fa fa-trash" aria-hidden="true"></i></a></td>

                        </tr>

                    @endforeach

                </tbody>

                @include('update_manager_team_modal')

            </table>

        </div><!-- /.box-body -->

    </div><!-- /.box -->

</section><!-- /.content -->

@endsection

@push('js')

    <script type="text/javascript">
        
        function edit_manager_team(team_member_id)
        {
            var url = '{{ route('user.managerEditTeam', ':id') }}';
            url = url.replace(':id', team_member_id);

            // display modal form for application editing
            $.get( url, function (data) 
            {
                //success data
                console.log(data);

                $('#edit-name').val(data.name);
                $('#edit-email').val(data.email);
                $('#edit-phone').val(data.phone);
                $('#hidden-team-member-id').val(team_member_id);

            });

        }

    </script>

@endpush
