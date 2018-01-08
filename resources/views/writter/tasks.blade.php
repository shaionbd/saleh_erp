@extends('layouts.app')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">

    <h1>All Tasks<small></small></h1>

    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i></a></li>
        <li class="active">Tasks</li>
    </ol>

</section>

<!-- Main content -->
<section class="content">
    <!-- Your Page Content Here -->
	<section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-warning">
            <div class="box-header with-border">
              <h3 class="box-title">Tasks Activity</h3>
            </div><!-- /.box-header -->
            <div class="box-body">
              <table class="table table-bordered ">
                <tr>
                  <th width="33%" class="text-center">Pending Tasks</th>
                  <th width="33%" class="text-center">On Going Tasks</th>
                  <th width="33%" class="text-center">Submitted Tasks</th>
                </tr>

                <tr>
                  <td>
                  	@foreach($pendingTasks as $pendingTask)
                      <div class="box box-solid bg-green-gradient task" data-taskid="{{ $pendingTask->id }}" data-tasktype="pending" data-url="{{ route('task.pending') }}">
                        <div class="box-header">
                          <i class="fa fa-tasks"></i>
                          <h3 class="box-title">
                            @php
                              $item = App\Item::where('id', $pendingTask->item_id)->first();
                            @endphp
                            <b>{{ $item->name }}</b>
                          </h3>
                          <!-- tools box -->
                          <div class="pull-right box-tools">
                            <!-- button with a dropdown -->
                            <form action="{{ route('user.pending_status_change') }}" method="post">
                              <input type="hidden" name="task_id" value="{{ $pendingTask->id }}">
                              <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                              <button type="submit" data-toggle="tooltip" title="accept" class="btn btn-success btn-sm" name="accept"><i class="fa fa-check"></i></button>
                              <button type="submit" data-toggle="tooltip" title="decline" class="btn btn-danger btn-sm" name="decline"><i class="fa fa-times"></i></button>
                              {{ csrf_field() }}
                            </form>
                          </div><!-- /. tools -->
                        </div><!-- /.box-header -->
                      </div>
                  	@endforeach
                  </td>

                  <td>
                  	@foreach($onGoingTasks as $onGoingTask)
  				          <div class="box box-warning">
  		                <div class="text-center box-header">
  		                  <i class="fa fa-tasks"></i>
  		                  <h3 class="box-title">
  		                  	@php
  		                  		$item = App\Item::where('id', $onGoingTask->item_id)->first();
  		                  	@endphp
  		                  	{{ $item->name }}
  		                  </h3>

  		                </div>

  		                <div class="box-body">
  		                  	<div class="callout callout-warning">
                            @if($onGoingTask->on_revision)
                              <strong><p align="center">Task is on revision</p></strong><br>
                            @endif
  		                  		<p><strong>Start Date: </strong>{{ $onGoingTask->start_date }}</p>
  		                  		<p><strong>End Date: </strong>{{ $onGoingTask->end_date }}</p>
  		                  		<p><strong>Words: </strong>{{ $onGoingTask->word_counts }}</p>
  			                    <p>{{ $onGoingTask->description }}</p>

  		                  	</div>
  		                </div>
  		                <div class="text-center box-footer clearfix">
                        <a href="javascript:void(0)" class="btn btn-success" data-toggle="modal" data-target="#task_submit_{{ $onGoingTask->id }}">Submit<i class="fa fa-arrow-circle-right"></i></a>

                        <div id="task_submit_{{ $onGoingTask->id }}" class="modal fade" role="dialog">
                          <div class="modal-dialog">
                            <form action="{{ route('user.onGoingTask_submit') }}" method="post" enctype="multipart/form-data">
                              <!-- Modal content-->
                              <div class="box box-warning">
                                <div class="modal-header">
                                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                                  <h4 class="modal-title">Submit your content file</h4>
                                </div>
                                <div class="modal-body">
                                  <div class="form-group text-left">
                                    <label for="submit_file">Upload</label>
                                    <input type="file" id="submit_file" name="submitted_file" class="form-control" accept=".xlsx,.xls,.doc, .docx,.ppt, .pptx,.txt,.pdf" required>
                                    <p class="text-danger">*Upload .xlsx,.xls,.doc, .docx,.ppt, .pptx,.txt,.pdf file</p>
                                  </div>
                                  <input type="hidden" name="task_id" value="{{ $onGoingTask->id }}">
                                  <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                                  {{ csrf_field() }}
                                </div>
                                <div class="modal-footer text-center">
                                  <button type="submit" class="btn btn-success">Submit</button>
                                  <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                </div>
                              </div>
                            </form>
                          </div>
                        </div>
                      </div>
                	  </div>
                  	@endforeach
                  </td>

                  <td>
                    @foreach($submittedTasks as $submittedTask)
                      @php
                      $status = App\ItemSubmission::where('task_id', $submittedTask->id)->orderBy('id', 'desc')->first();
                      @endphp
                      @if($status->manager_rivision <= 1)
                        <div class="box box-primary">
                          <div class="text-center box-header">
                            <i class="fa fa-tasks"></i>
                            <h3 class="box-title">
                              @php
                                $item = App\Item::where('id', $submittedTask->item_id)->first();
                              @endphp
                              {{ $item->name }}
                            </h3>

                          </div>

                          <div class="box-body">
                              @if($status->manager_rivision == 0)
                              <div class="callout callout-info">
                              @else
                              <div class="callout callout-success">
                              @endif
                                <p><strong>Submitted Date: </strong>{{ $submittedTask->start_date }}</p>

                                @if($status->re_submission_date)
                                  <p><strong>End Date: </strong>{{ $status->re_submission_date }}</p>
                                @else
                                  <p><strong>End Date: </strong>{{ $submittedTask->end_date }}</p>
                                @endif

                                <p><strong>Words: </strong>{{ $submittedTask->word_counts }}</p>
                                <p><strong>Submitted Date: </strong>{{ $submittedTask->submission_date }}</p>

                                <p><strong>Revision By: </strong>
                                @if($status->manager_rivision == 0)
                                  Manager
                                @elseif($status->manager_rivision == 1 && $status->admin_rivision == 0)
                                  Admin
                                @endif
                                </p>


                              </div>
                          </div>
                        </div>
                      @endif
                    @endforeach
                  </td>

                </tr>

              </table>

              <table class="table table-bordered">
                <tr>
                  <th class="text-center">Revision Tasks</th>
                </tr>

                <tr>
                  <td>
                    <div class="row">
                      @foreach($submittedTasks as $submittedTask)
                        @php
                        $status = App\ItemSubmission::where('task_id', $submittedTask->id)->orderBy('id', 'desc')->first();
                        @endphp
                        @if($status->manager_rivision == 2)
                          <div class="col-md-4">
                            <div class="box box-danger">
                              <div class="text-center box-header">
                                <i class="fa fa-tasks"></i>
                                <h3 class="box-title">
                                  @php
                                    $item = App\Item::where('id', $submittedTask->item_id)->first();
                                  @endphp
                                  {{ $item->name }}
                                </h3>

                              </div>

                              <div class="box-body">
                                  <div class="callout callout-danger">
                                    <p><strong>Words: </strong>{{ $submittedTask->word_counts }}</p>
                                    <p><strong>Re-submission Date: </strong><br>{{ $status->re_submission_date }}</p>

                                    @if($status->admin_rivision == 2)
                                      <p><strong>Task revised by: </strong>Admin</p>
                                      <p>{{ $status->admin_revision_description }}</p>
                                    @else
                                      <p><strong>Task revised by: </strong>Manager</p>
                                      <p>{{ $status->manager_revision_description }}</p>
                                    @endif
                                    <p><a href="{{ asset('storage/app/public/tasks/'.$status->file) }}" download="{{ $status->file }}">View Task file</a></p>
                                  </div>
                              </div>
                              <div class="text-center box-footer clearfix">
                                <form action="{{ route('user.revision_pending_status_change') }}" method="post">

                                <input type="hidden" name="task_id" value="{{ $submittedTask->id }}">
                                <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">

                                <button type="submit" class="btn btn-success" name="accept" value="accept">Rivision <i class="fa fa-arrow-circle-right"></i></button>
                                {{ csrf_field() }}
                              </form>
                              </div>
                            </div>
                          </div>
                        @endif
                      @endforeach
                    </div>
                  </td>
                </tr>

              </table>

            </div><!-- /.box-body -->

          </div><!-- /.box -->
        </div><!-- /.col -->
      </div><!-- /.row -->
  </section><!-- / .section -->

</section><!-- /.content -->

<div id="taskModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-body">
        <preloader></preloader>
        <div id="pending-body">

        </div>
      </div>
    </div>

  </div>
</div>

@endsection



<div id="demo" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <!-- Modal content-->
    <div class="modal-content">
      <div id="pending-body-dmo" class="modal-body">
        {{-- <preloader></preloader> --}}
        <h3 class="text-center">About Section Of Demo</h3>
        <div class="progress">
          <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:40%">
              40% complete
            </div>
          </div>
        <div class="row">
          <div class="task-info col-md-8">
            <div class="row">
              <div class="col-md-4">
                <p><strong>Tracing ID: </strong>1235</p>
                <p><strong>Project: </strong>Demo</p>
              </div>
              <div class="col-md-4">
                <p><strong>Article Title: </strong>1235</p>
                <p><strong>Article Type: </strong>1235</p>
              </div>
              <div class="col-md-4">
                <p><strong>Word Count: </strong>1235</p>
                <p><strong>Manager: </strong>1235</p>
              </div>
            </div>
            <p>
              <strong>Admin Instruction: </strong>
              <div class="well">
                <i>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</i>
              </div>
            </p>
            <p><strong>Manager Instruction: </strong><br>
              <div class="well">
                <i>Lorem ipsum dolor sit amet, enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</i>
              </div>
            </p>
          </div>
          <div class="task-info col-md-4">
            <p><strong>Estimate Earning: </strong> $25</p>
            <p><strong>Assign Date: </strong>1st Jan 2018 at 11:00 PM</p>
            <p><strong>Submission Date: </strong>7th Jan 2018 at 12:00 AM</p>
            <p><strong>Time Tracker: </strong><span id="tracker"></span></p>
            <div class="row">
              <div class="col-md-12">
                <div class="btn-group btn-group-justified">
                  <div class="btn-group">
                    <button type="button" class="btn btn-danger">6 Days</button>
                  </div>
                  <div class="btn-group">
                    <button type="button" class="btn btn-primary">22 Hours</button>
                  </div>
                  <div class="btn-group">
                    <button type="button" class="btn btn-primary">47 Minutes</button>
                  </div>
                </div>
              </div>
            </div>
            <br>
            <a href="#" class="btn btn-warning btn-block">Request for Time Extend</a>
            <br>
            <p><strong>Total Earning: </strong>$23</p>
            <p><strong>Total Panalty: </strong>$2</p>
          </div>

          <div class="col-md-12">
            <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Item Submission</h3>
                </div><!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                  <table class="table table-hover">
                    <tr>
                      <th>File</th>
                      <th>Status</th>
                      <th>Manager Rivision</th>
                      <th>Admin Rivision</th>
                      <th>Reason</th>
                      <th>Submission Date</th>
                      <th>Re-submission Date</th>
                    </tr>
                    <tr>
                      <td><a href="#">hello.docx</a></td>
                      <td>Revisioning by Manager</td>
                      <td>--</td>
                      <td>--</td>
                      <td>--</td>
                      <td>17th December 2017 at 12:00pm</td>
                      <td>--</td>
                    </tr>
                  </table>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
          </div>
        </div>
      </div>
    </div>

  </div>
</div>
