@extends('layouts.app')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">

    <h1>Archive Details<small></small></h1>

    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i></a></li>
        <li class="active">archives</li>
    </ol>

</section>

<!-- Main content -->
<section class="content">

 	<div class="box box-info">

        <div class="box-header with-border">
        	@php
        		$monthNum  = $month;
				$dateObj   = DateTime::createFromFormat('!m', $monthNum);
				$monthName = $dateObj->format('F');
        	@endphp
            <h3 class="box-title">{{ $monthName }}, {{ $year }}</h3>
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
                    @foreach($tasks as $task)

                    @endforeach
                </tbody>

            </table>

        </div><!-- /.box-body -->

    </div><!-- /.box -->   
	
</section><!-- /.content -->

@endsection
