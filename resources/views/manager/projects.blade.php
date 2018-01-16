@extends('layouts.app')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">

    <h1>Projects<small>All projects</small></h1>

    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Manager</a></li>
        <li class="active">Projects</li>
    </ol>

</section>

<!-- Main content -->
<section class="content">

    @if(sizeof($projects) > 0)
    <!-- Your Page Content Here -->
    <div class="box">
        <div class="box-header">
          <h3 class="box-title">All Projects</h3>
        </div><!-- /.box-header -->
        <div class="box-body">
          <table class="datatable table table-bordered table-striped">
            <thead>
              <tr>
                <th width="130">Name</th>
                <th>Description</th>
                <th width="80">Word Counts</th>
                <th width="80">Price</th>
                <th width="120">Start Date</th>
                <th width="120">End Date</th>
                <th width="100">Make Item</th>
              </tr>
            </thead>
            <tbody>
              @foreach($projects as $project)
                <tr>
                  <td>{{ $project->name }}</td>
                  <td>{{ $project->description }}</td>
                  <td>{{ $project->word_counts }}</td>
                  <td>{{ $project->price }}</td>
                  <td>{{ date("M d Y, h:i A", strtotime($project->start_date)) }}</td>
                  <td>{{ date("M d Y, h:i A", strtotime($project->end_date)) }}</td>
                  <td><a href="{{ route('item_create.complete', $project->id) }}" class="btn btn-success btn-sm" title="make items"><i class="fa fa-check"></i> Complete</a></td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>

    <div class="row">
      <div class="col-md-8 col-md-offset-2">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title">Create Item</h3>
          </div><!-- /.box-header -->
          <div class="box-body">
            <form role="form" action="{{ route('create.item') }}" method="post">
                <div class="box-body">
                  <div class="form-group">
                    <label for="project_id">Select Project</label>
                    <select class="form-control" name="project_id">
                      @foreach($projects as $project)
                        <option value="{{ $project->id }}">{{ $project->name }}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="name">Item Name</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Create an item of project" required>
                  </div>

                  <div class="form-group">
                    <label for="item_name">Instruction</label>
                    <textarea class="form-control" rows="5" name="description" required></textarea>
                  </div>

                  <div class="form-group">
                    <label for="item_name">Word Counts</label>
                    <input type="number" name="word_counts" class="form-control" placeholder="5000" required>
                  </div>

                  <div class="form-group">
                    <label for="item_name">Item Type</label>
                    <input type="text" name="type" class="form-control" placeholder="Informative" required>
                  </div>

                  <div class="form-group">
                    <label for="item_name">Start Date</label>
                    <input type="date" name="start_date" class="form-control" required>
                  </div>
                  <div class="form-group">
                    <label for="item_name">Start Time</label>
                    <input type="time" name="start_time" class="form-control" required>
                  </div>

                  <div class="form-group">
                    <label for="item_name">End Date</label>
                    <input type="date" name="end_date" class="form-control" required>
                  </div>
                  <div class="form-group">
                    <label for="item_name">End Time</label>
                    <input type="time" name="end_time" class="form-control" required>
                  </div>

                  <div class="form-group">
                    <label for="item_name">Price</label>
                    <input type="text" name="price" class="form-control" required>
                  </div>
                </div><!-- /.box-body -->

                <div class="box-footer">
                  <button type="submit" class="btn btn-primary">Submit</button>
                </div>

                {{ csrf_field() }}
              </form>
          </div>
        </div>
      </div>
    </div>
    @else
      <div class="box">
        <div class="box-body">
          <h3 class="text-center text-danger"><b>No assign projects found!</b></h3>
        </div>
      </div>
    @endif

</section><!-- /.content -->

@endsection
