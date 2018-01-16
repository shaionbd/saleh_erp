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
                  <th width="33%" class="text-center">Pending Items</th>
                  <th width="33%" class="text-center">Assign Task</th>
                  <th width="33%" class="text-center">Writter Pending Tasks</th>
                </tr>

                <tr>
                  <td>
                  	@foreach($pendingItems as $pendingItem)
                      <div class="box box-solid bg-green-gradient">
                        <div class="box-header">

                            <div class="col-md-8 item" style="background: transparent"  data-itemid="{{ $pendingItem->id }}" data-itemtype="pending" data-url="{{ route('item.pending') }}">
                              <div class="row">
                                <div class="col-md-2">
                                  <i class="fa fa-tasks fa-2x"></i>
                                </div>
                                <div class="col-md-10">
                                  <h3 class="box-title">
                                    <b>{{ $pendingItem->name }}</b>
                                  </h3>
                                </div>
                              </div>
                            </div>
                            <div class="col-md-4">
                              <!-- tools box -->
                              <div class="pull-right box-tools">
                                <!-- button with a dropdown -->
                                <form action="{{ route('manager.pending_status_change') }}" method="post">
                                  <input type="hidden" name="item_id" value="{{ $pendingItem->id }}">
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
                    @foreach($assignTasks as $assignTask)
                      @if($assignTask->writter_id && $assignTask->process_status == 8)
                      <div class="box box-solid bg-red-gradient">
                      @else
                      <div class="box box-solid bg-yellow-gradient">
                      @endif
                        <div class="box-header">

                            <div class="col-md-9 task" style="background: transparent"  data-taskid="{{ $assignTask->id }}" data-tasktype="assign" data-url="{{ route('task.assign') }}">
                              <div class="row">
                                <div class="col-md-2">
                                  <i class="fa fa-tasks fa-2x"></i>
                                </div>
                                <div class="col-md-10">
                                  <h3 class="box-title">
                                    @php
                                      $item = App\Item::where('id', $assignTask->item_id)->first();
                                    @endphp
                                    <b>{{ $item->name }}</b>
                                  </h3>
                                </div>
                              </div>
                            </div>
                            <div class="col-md-3">
                              <!-- tools box -->
                              <div class="pull-right box-tools">
                                <a href="javascript:void(0)" class="btn btn-warning" data-toggle="modal" data-target="#task_assign_{{ $assignTask->id }}">Assign <i class="fa fa-arrow-circle-right"></i></a>
                                <div id="task_assign_{{ $assignTask->id }}" class="modal fade" role="dialog">
                                  <div class="modal-dialog">
                                    @php
                                      $writters = App\User::where('supervisor', Auth::user()->id)->get();
                                    @endphp
                                    <form action="{{ route('task.assign_send') }}" method="post">
                                      <!-- Modal content-->
                                      <div class="box box-warning">
                                        <div class="modal-header">
                                          <button type="button" class="close" data-dismiss="modal">&times;</button>
                                          <h4 class="modal-title">Assign User</h4>
                                        </div>
                                        <div class="modal-body">
                                          <div class="form-group">
                                            <label style="color: #555">Writter</label>
                                            <select class="form-control select2" name="writter_id" style="width: 100%;">
                                              @foreach ($writters as $writter)
                                                <option value="{{$writter->id}}">{{$writter->name}}</option>;
                                              @endforeach
                                        </select>
                                          </div>
                                          <div class="form-group">
                                            <label style="color: #555">Instruction</label>
                                            <textarea class="form-control" rows="5" name="description" required></textarea>
                                          </div>
                                          <div class="form-group">
                                            <label style="color: #555">Submission Date</label>
                                            <input type="date" name="end_date" class="form-control" required/>
                                           </div>
                                           <div class="form-group">
                                            <label style="color: #555">Submission Time</label>
                                            <input type="time" name="end_time" class="form-control" required/>
                                           </div>
                                           <input type="hidden" name="task_id" value="{{$assignTask->id}}">
                                           <input type="hidden" name="user_id" value="{{Auth::user()->id}}">
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

                              </div><!-- /. tools -->
                            </div>

                        </div><!-- /.box-header -->
                      </div>
                  	@endforeach
                  </td>

                  <td>
                    @foreach($writterPendingTasks as $writterPendingTask)

                      <div class="box box-solid bg-blue-gradient">
                        <div class="box-header">

                            <div class="col-md-12 task" style="background: transparent"  data-taskid="{{ $writterPendingTask->id }}" data-tasktype="writter_pending" data-url="{{ route('task.writter_pending') }}">
                              <div class="row">
                                <div class="col-md-2">
                                  <i class="fa fa-tasks fa-2x"></i>
                                </div>
                                <div class="col-md-10">
                                  <h3 class="box-title">
                                    @php
                                      $item = App\Item::where('id', $writterPendingTask->item_id)->first();
                                    @endphp
                                    <b>{{ $item->name }}</b>
                                  </h3>
                                </div>
                              </div>
                            </div>
                        </div><!-- /.box-header -->
                      </div>
                  	@endforeach
                  </td>
                </tr>

              </table>

              <table class="table table-bordered">
                <tr>
                  <th width="33%" class="text-center">OnGoing Tasks</th>
                  <th width="33%" class="text-center">Writter Submitted Tasks</th>
                  <th width="33%" class="text-center">Admin Submitted Tasks</th>
                </tr>

                <tr>
                  <td>
                  	@foreach($onGoingTasks as $onGoingTask)
                      @if($onGoingTask->on_revision)
                        <div class="box box-solid bg-red-gradient">
                      @else
                        <div class="box box-solid bg-yellow-gradient">
                      @endif
                        <div class="box-header">
                            <div class="col-md-12 task" style="background: transparent"  data-taskid="{{ $onGoingTask->id }}" data-tasktype="managerOnGoing" data-url="{{ route('task.manager_on_going') }}">
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
                        </div><!-- /.box-header -->
                      </div>
                  	@endforeach
                  </td>
                  <td>
                    <div class="row">
                      @foreach($submittedTasks as $submittedTask)
                        @php
                          $status = App\ItemSubmission::where(['task_id'=>$submittedTask->id])->orderBy('id', 'desc')->first();
                        @endphp
                        @if($status->manager_rivision == 0)
                          <div class="col-md-12">
                              <div class="box box-solid bg-red-gradient">
                                <div class="box-header">
                                    <div class="col-md-8 task" style="background: transparent"  data-taskid="{{ $submittedTask->id }}" data-tasktype="manager_review" data-url="{{ route('task.manager_review') }}">
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
                                      <div class="pull-right box-tools">
                                        <!-- button with a dropdown -->
                                        <a href="{{asset('storage/app/public/tasks/'.$status->file)}}" class="btn btn-danger">Download</a>
                                      </div>
                                    </div>

                                </div><!-- /.box-header -->
                              </div>
                          </div>
                        @endif
                      @endforeach
                    </div>
                  </td>

                  <td>
                    @foreach($submittedTasks as $submittedTask)
                      @php
                        $status = App\ItemSubmission::where('task_id', $submittedTask->id)->orderBy('id', 'desc')->first();
                      @endphp
                      @if($status->manager_rivision == 1 && $status->admin_rivision == 0)

                        <div class="box box-solid bg-green-gradient">
                          <div class="box-header">

                              <div class="col-md-12 task" style="background: transparent"  data-taskid="{{ $submittedTask->id }}" data-tasktype="manager_submitted" data-url="{{ route('task.manager_submitted') }}">
                                <div class="row">
                                  <div class="col-md-2">
                                    <i class="fa fa-tasks fa-2x"></i>
                                  </div>
                                  <div class="col-md-10">
                                    <h3 class="box-title">
                                      @php
                                        $item = App\Item::where('id', $submittedTask->item_id)->first();
                                      @endphp
                                      <b>{{ $item->name }} review by Admin</b>
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
                        @if($status->manager_rivision == 1 && $status->admin_rivision)
                          <div class="col-md-4">
                              <div class="box box-solid bg-red-gradient">
                                <div class="box-header">
                                    <div class="col-md-12 task" style="background: transparent"  data-taskid="{{ $submittedTask->id }}" data-tasktype="manager_revision" data-url="{{ route('task.manager_revision') }}">
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

<div class="modal fade">
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
</div>
