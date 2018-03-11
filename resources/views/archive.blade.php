@extends('layouts.app')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">

    <h1>Archives<small></small></h1>

    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i></a></li>
        <li class="active">archives</li>
    </ol>

</section>

<!-- Main content -->
<section class="content">

    <div class="box box-info">

        <div class="box-header with-border">
            <h3 class="box-title">Month ({{ $month }} - {{ $year }})</h3>
        </div><!-- /.box-header -->

        <div class="box-body">

            <table class="table table-bordered">

                <thead>

                    <tr>
                        <th>Task Assigned</th>
                        <th>Task Completed</th>
                        <th>Total Earning</th>
                        <th>Penalty</th>
                        <th>Earning</th>
                        <th>Rating</th>
                        <th>Action</th>

                    </tr>

                </thead>

                <tbody>

                    @foreach($archives as $archive)
                    
                        <tr>

                            <td>{{ $archive->assigned_task }}</td>
                            <td>{{ $archive->delivered_task }}</td>
                            <td>{{ $archive->total_earning }}</td>
                            <td>{{ $archive->total_penalty }}</td>
                            <td>{{ $archive->total_earning - $archive->total_penalty }}</td>
                            <td>0</td>
                            <td><a href="{{ route('user.archiveDetails',  $year."-".$month) }}" class="btn btn-info">Details</a></td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div><!-- /.box-body -->

    </div><!-- /.box -->

    @php
        if(Auth::user()->role == 2) {$user_id = 'manager_id';}else{$user_id = 'writter_id';}
        if($type=="current"||$type=="prev"){if($month>0){$month--;if($month==0){$year--;$month=12;}}}else{if($month<=12){$month++;if($month==13){$year++;$month=1;}}}
        $archives=App\Task::select('id',DB::raw("SUM(MONTH(start_date) = $month AND YEAR(start_date) = $year AND process_status = 4 AND is_accepted = 1) AS assigned_task"),DB::raw("SUM(MONTH(end_date) = $month AND YEAR(end_date) = $year AND process_status = 4 AND is_accepted = 1) AS delivered_task"),DB::raw("(SELECT SUM(payments.writter_share) FROM tasks LEFT JOIN payments ON tasks.id = payments.task_id WHERE MONTH(tasks.end_date) = $month AND YEAR(tasks.end_date) = $year AND tasks.process_status = 4 AND tasks.is_accepted = 1 GROUP BY payments.$user_id) AS total_earning"),DB::raw("(SELECT SUM(payments.writter_penalty) FROM tasks LEFT JOIN payments ON tasks.id = payments.task_id WHERE MONTH(tasks.submission_date) = $month AND YEAR(tasks.submission_date) = $year AND tasks.process_status = 4 AND tasks.is_accepted = 1 GROUP BY payments.$user_id) AS total_penalty"))->get();
    @endphp

    <div class="box box-success">

        <div class="box-header with-border">
            <h3 class="box-title">Month ({{ $month }} - {{ $year }})</h3>
        </div><!-- /.box-header -->

        <div class="box-body">

            <table class="table table-bordered">

                <thead>

                    <tr>

                        <th>Task Assigned</th>
                        <th>Task Completed</th>
                        <th>Total Earning</th>
                        <th>Penalty</th>
                        <th>Earning</th>
                        <th>Rating</th>
                        <th>Action</th>

                    </tr>

                </thead>

                <tbody>

                    @foreach($archives as $archive)
                    
                        <tr>

                            <td>{{ $archive->assigned_task }}</td>
                            <td>{{ $archive->delivered_task }}</td>
                            <td>{{ $archive->total_earning }}</td>
                            <td>{{ $archive->total_penalty }}</td>
                            <td>{{ $archive->total_earning - $archive->total_penalty }}</td>
                            <td>0</td>
                            <td><a href="{{ route('user.archiveDetails',  $year."-".$month) }}" class="btn btn-info">Details</a></td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div><!-- /.box-body -->

    </div><!-- /.box -->

    @php
        if(Auth::user()->role == 2) {$user_id = 'manager_id';}else{$user_id = 'writter_id';}
        if($type=="current"||$type=="prev"){if($month>0){$month--;if($month==0){$year--;$month=12;}}}else{if($month<=12){$month++;if($month==13){$year++;$month=1;}}}
        $archives=App\Task::select('id',DB::raw("SUM(MONTH(start_date) = $month AND YEAR(start_date) = $year AND process_status = 4 AND is_accepted = 1) AS assigned_task"),DB::raw("SUM(MONTH(end_date) = $month AND YEAR(end_date) = $year AND process_status = 4 AND is_accepted = 1) AS delivered_task"),DB::raw("(SELECT SUM(payments.writter_share) FROM tasks LEFT JOIN payments ON tasks.id = payments.task_id WHERE MONTH(tasks.end_date) = $month AND YEAR(tasks.end_date) = $year AND tasks.process_status = 4 AND tasks.is_accepted = 1 GROUP BY payments.$user_id) AS total_earning"),DB::raw("(SELECT SUM(payments.writter_penalty) FROM tasks LEFT JOIN payments ON tasks.id = payments.task_id WHERE MONTH(tasks.submission_date) = $month AND YEAR(tasks.submission_date) = $year AND tasks.process_status = 4 AND tasks.is_accepted = 1 GROUP BY payments.$user_id) AS total_penalty"))->get();
    @endphp

    <div class="box box-danger">

        <div class="box-header with-border">
            <h3 class="box-title">Month ({{ $month }} - {{ $year }})</h3>
        </div><!-- /.box-header -->

        <div class="box-body">

            <table class="table table-bordered">

                <thead>

                    <tr>
                        <th>Task Assigned</th>
                        <th>Task Completed</th>
                        <th>Total Earning</th>
                        <th>Penalty</th>
                        <th>Earning</th>
                        <th>Rating</th>
                        <th>Action</th>

                    </tr>

                </thead>

                <tbody>

                    @foreach($archives as $archive)
                    
                        <tr>

                            <td>{{ $archive->assigned_task }}</td>
                            <td>{{ $archive->delivered_task }}</td>
                            <td>{{ $archive->total_earning }}</td>
                            <td>{{ $archive->total_penalty }}</td>
                            <td>{{ $archive->total_earning - $archive->total_penalty }}</td>
                            <td>0</td>
                            <td><a href="{{ route('user.archiveDetails', $year."-".$month) }}" class="btn btn-info">Details</a></td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div><!-- /.box-body -->

    </div><!-- /.box -->

    <div class="row" style="margin-bottom: 20px;">

        <div class="col-md-3 col-md-offset-2">

            <a href="{{ url("/writter/archives/prev/$month/$year") }}" class="btn btn-primary">< PREV</a>

        </div>

        <div class="col-md-3 col-md-offset-4">

            <a href="{{ url("/writter/archives/next/$month/$year") }}" class="btn btn-primary">NEXT ></a>

        </div>

    </div>
	
</section><!-- /.content -->

@endsection
