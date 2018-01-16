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
                      <div class="box box-solid bg-green-gradient">
                        <div class="box-header">

                            <div class="col-md-8 task" style="background: transparent"  data-taskid="{{ $pendingTask->id }}" data-tasktype="pending" data-url="{{ route('task.pending') }}">
                              <div class="row">
                                <div class="col-md-2">
                                  <i class="fa fa-tasks fa-2x"></i>
                                </div>
                                <div class="col-md-10">
                                  <h3 class="box-title">
                                    @php
                                      $item = App\Item::where('id', $pendingTask->item_id)->first();
                                    @endphp
                                    <b>{{ $item->name }}</b>
                                  </h3>
                                </div>
                              </div>
                            </div>
                            <div class="col-md-4">
                              <!-- tools box -->
                              <div class="pull-right box-tools">
                                <!-- button with a dropdown -->
                                <form action="{{ route('user.pending_status_change') }}" method="post">
                                  <input type="hidden" name="task_id" value="{{ $pendingTask->id }}">
                                  <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                                  <button type="submit" data-toggle="tooltip" title="accept" class="btn btn-success btn-sm" name="accept" value="accept"><i class="fa fa-check"></i></button>
                                  <button type="submit" data-toggle="tooltip" title="decline" class="btn btn-danger btn-sm" name="decline" value="decline"><i class="fa fa-times"></i></button>
                                  {{ csrf_field() }}
                                </form>
                              </div><!-- /. tools -->
                            </div>

                        </div><!-- /.box-header -->
                      </div>
                  	@endforeach
                  </td>

                  <td>
                  	@foreach($onGoingTasks as $onGoingTask)
                      @if($onGoingTask->on_revision)
                        <div class="box box-solid bg-red-gradient">
                      @else
                        <div class="box box-solid bg-yellow-gradient">
                      @endif
                        <div class="box-header">
                            <div class="col-md-8 task" style="background: transparent"  data-taskid="{{ $onGoingTask->id }}" data-tasktype="onGoing" data-url="{{ route('task.on_going') }}">
                              <div class="row">
                                <div class="col-md-2">
                                  <i class="fa fa-tasks fa-2x"></i>
                                </div>
                                <div class="col-md-10">
                                  <h3 class="box-title">
                                    @php
                                      $item = App\Item::where('id', $onGoingTask->item_id)->first();
                                    @endphp
                                    <b>{{ $item->name }}</b>
                                  </h3>
                                </div>
                              </div>
                            </div>
                            <div class="col-md-4">
                                <a href="javascript:void(0)" class="btn btn-warning" data-toggle="modal" data-target="#task_submit_{{ $onGoingTask->id }}">Submit <i class="fa fa-arrow-circle-right"></i></a>
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
                        </div><!-- /.box-header -->
                      </div>
                  	@endforeach
                  </td>

                  <td>
                    @foreach($submittedTasks as $submittedTask)
                      @php
                        $status = App\ItemSubmission::where('task_id', $submittedTask->id)->orderBy('id', 'desc')->first();
                      @endphp
                      @if($status->manager_rivision <= 1)
                        @if($status->manager_rivision == 0)
                          <div class="box box-solid bg-blue-gradient">
                        @else
                          <div class="box box-solid bg-green-gradient">
                        @endif
                          <div class="box-header">

                              <div class="col-md-12 task" style="background: transparent"  data-taskid="{{ $submittedTask->id }}" data-tasktype="submitted" data-url="{{ route('task.submitted') }}">
                                <div class="row">
                                  <div class="col-md-2">
                                    <i class="fa fa-tasks fa-2x"></i>
                                  </div>
                                  <div class="col-md-10">
                                    <h3 class="box-title">
                                      @php
                                        $item = App\Item::where('id', $submittedTask->item_id)->first();
                                      @endphp
                                      <b>{{ $item->name }} review by
                                        @if($status->manager_rivision == 0)
                                          Manager
                                        @else
                                          Admin
                                        @endif
                                      </b>
                                    </h3>
                                  </div>
                                </div>
                              </div>
                          </div><!-- /.box-header -->
                        </div>
                      @endif
                    @endforeach
                  </td>

                </tr>

              </table>

              <table class="table table-bordered">
                <tr>
                  <th class="text-center">Review Tasks</th>
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
                              <div class="box box-solid bg-red-gradient">
                                <div class="box-header">
                                    <div class="col-md-8 task" style="background: transparent"  data-taskid="{{ $submittedTask->id }}" data-tasktype="review" data-url="{{ route('task.review') }}">
                                      <div class="row">
                                        <div class="col-md-2">
                                          <i class="fa fa-tasks fa-2x"></i>
                                        </div>
                                        <div class="col-md-10">
                                          <h3 class="box-title">
                                            @php
                                              $item = App\Item::where('id', $submittedTask->item_id)->first();
                                            @endphp
                                              <b>{{ $item->name }}</b>
                                          </h3>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="col-md-4">
                                      <form action="{{ route('user.revision_pending_status_change') }}" method="post">
                                        <input type="hidden" name="task_id" value="{{ $submittedTask->id }}">
                                        <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                                        <button type="submit" class="btn btn-danger" name="accept" value="accept">Rivision <i class="fa fa-arrow-circle-right"></i></button>
                                        {{ csrf_field() }}
                                      </form>
                                    </div>
                                </div><!-- /.box-header -->
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
      <div class="modal-body task-body">
        <preloader></preloader>
        <div id="task-body">

        </div>
      </div>
    </div>

  </div>
</div>

@endsection
