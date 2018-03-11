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
                    <th width="25%" class="text-center">Create Orders</th>
                    <th width="25%" class="text-center">Pending Orders</th>
                    <th width="25%" class="text-center">Process Orders</th>
                    <th width="25%" class="text-center">Assigned Orders</th>
                </tr>

                <tr>
                    <td>
                        <div class="box box-solid bg-green-gradient">
                            <div class="box-header">
                                <a class="btn-block" href="javascript:void()" style="background: transparent; color: #fff" data-toggle="modal" data-target="#new-order-modal">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <i class="fa fa-first-order fa-2x"></i>
                                        </div>
                                        <div class="col-md-10">
                                            <h3 class="box-title">
                                                <b>Create a new Order</b>
                                            </h3>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </td>
                    <td> 
                        @foreach($projects as $project)
                        <div class="box box-solid bg-yellow-gradient">
                            <div class="box-header">

                                <div class="col-md-10 project" style="background: transparent"  data-projectid="{{ $project->id }}" data-projecttype="admin_pending" data-url="{{ route('project.admin_pending') }}">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <i class="fa fa-product-hunt fa-2x"></i>
                                        </div>
                                        <div class="col-md-10">
                                            <h3 class="box-title">
                                                <b>{{ $project->name }}</b>
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <!-- tools box -->
                                    <div class="pull-right box-tools">
                                        <!-- button with a dropdown -->
                                        <a href="javascript:void()" class="project btn btn-warning btn-xs" data-projectid="{{ $project->id }}" data-projecttype="admin_pending" data-url="{{ route('project.admin_pending') }}"><i class="fa fa-arrow-right"></i></a>
                                    </div><!-- /. tools -->
                                </div>

                            </div><!-- /.box-header -->
                        </div>
                        @endforeach
                    </td>
                    <td>
                        @foreach($processArticles as $processArticle)
                        <div class="box box-solid bg-blue-gradient">
                            <div class="box-header">

                                <div class="col-md-10 project" style="background: transparent"  data-projectid="{{ $processArticle->id }}" data-projecttype="admin_process" data-url="{{ route('project.admin_process') }}">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <i class="fa fa-product-hunt fa-2x"> </i>
                                        </div>
                                        <div class="col-md-10">
                                            <h3 class="box-title">
                                                <b>{{ $processArticle->name }}</b>
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <!-- tools box -->
                                    <div class="pull-right box-tools">
                                        <!-- button with a dropdown -->
                                        <a href="javascript:void()" class="project btn btn-primary btn-xs" data-projectid="{{ $processArticle->id }}" data-projecttype="admin_process" data-url="{{ route('project.admin_process') }}"><i class="fa fa-arrow-right"></i></a>
                                    </div><!-- /. tools -->
                                </div>

                            </div><!-- /.box-header -->
                        </div>
                        @endforeach
                    </td>

                    <td>
                        @foreach($assignedItems as $assignedItem)
                            @if($assignedItem->process_status == 3)
                            <div class="box box-solid bg-red-gradient">
                            @else
                            <div class="box box-solid bg-yellow-gradient">
                            @endif
                            <div class="box-header">

                                <div class="col-md-10 item" style="background: transparent"  data-itemid="{{ $assignedItem->id }}" data-itemtype="admin_assigned_item" data-url="{{ route('admin.assigned_item') }}">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <i class="fa fa-tasks fa-2x"> </i>
                                        </div>
                                        <div class="col-md-10">
                                            <h3 class="box-title">
                                                <b>{{ $assignedItem->name }}</b>
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <!-- tools box -->
                                    <div class="pull-right box-tools">
                                        <!-- button with a dropdown -->
                                        <a href="javascript:void()" class="item btn btn-warning btn-xs" data-itemid="{{ $assignedItem->id }}" data-itemtype="admin_assigned_item" data-url="{{ route('admin.assigned_item') }}"><i class="fa fa-arrow-right"></i></a>
                                    </div><!-- /. tools -->
                                </div>

                            </div><!-- /.box-header -->
                        </div>
                        @endforeach
                        @foreach($assignedArticles as $assignedArticle)
                        <div class="box box-solid bg-green-gradient">
                            <div class="box-header">

                                <div class="col-md-10 project" style="background: transparent"  data-projectid="{{ $assignedArticle->id }}" data-projecttype="admin_assigned_project" data-url="{{ route('admin.assigned_project') }}">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <i class="fa fa-product-hunt fa-2x"> </i>
                                        </div>
                                        <div class="col-md-10">
                                            <h3 class="box-title">
                                                <b>{{ $assignedArticle->name }}</b>
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <!-- tools box -->
                                    <div class="pull-right box-tools">
                                        <!-- button with a dropdown -->
                                        <a href="javascript:void()" class="project btn btn-success btn-xs" data-projectid="{{ $assignedArticle->id }}" data-projecttype="admin_assigned_project" data-url="{{ route('admin.assigned_project') }}"><i class="fa fa-arrow-right"></i></a>
                                    </div><!-- /. tools -->
                                </div>

                            </div><!-- /.box-header -->
                        </div>
                        @endforeach
                    </td>

                </tr>

              </table>

              <table class="table table-bordered">
                <tr>
                  <th width="33%" class="text-center">OnGoing Orders</th>
                  <th width="33%" class="text-center">Waiting Approval(Under Checking)</th>
                  <th width="33%" class="text-center">Submitted Tasks(Client)</th>
                </tr>

                <tr>
                  <td>
                  	
                  </td>
                  <td>
                    <div class="row">
                      
                    </div>
                  </td>

                  <td>
                   
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

<div id="new-order-modal" class="modal fade" role="dialog">
    
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-body">
            <form action="{{ route('admin.create_package') }}" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="name">Article Name:</label>
                    <input type="text" class="form-control" id="name" name="name">
                </div>
                <div class="form-group">
                    <label for="type">Article Type:</label>
                    <select id="type" name="type" class="form-control">
                        @foreach ($types as $type )
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="words">Total Words:</label>
                    <input type="number" class="form-control" id="words" name="words">
                </div>
                <div class="form-group">
                    <label for="price">Price:</label>
                    <input type="text" class="form-control" id="price" name="price">
                </div>
                <div class="form-group">
                    <label for="name">Ending Date:</label>
                    <input type="date" class="form-control" id="date" name="date">
                </div>
                <div class="form-group">
                    <label for="time">Ending Time:</label>
                    <input type="time" class="form-control" id="time" name="time">
                </div>
                <div class="form-group">
                    <label for="project_summary">Article Details: <input type="file" name="project_summary_file"></label>
                    <textarea class="form-control" name="project_summary"></textarea>
                </div>
                {{ csrf_field() }}
                <button type="submit" class="btn btn-default">Submit</button>
            </form>
        </div>
    </div>
  </div>
</div>

<script src="{{ URL::asset('public/js/tinymce/tinymce.min.js') }}"></script>
<script>tinymce.init({ selector:'textarea' });</script>

@endsection
