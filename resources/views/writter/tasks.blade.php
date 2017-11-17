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
            <div class="col-md-8">
              <div class="box box-danger">
                <div class="box-header with-border">
                  <h3 class="box-title">Tasks Activity</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <table class="table table-bordered">
                    <tr>
                      <th width="33%" class="text-center">Pending Tasks</th>
                      <th width="33%" class="text-center">On Going Tasks</th>
                      <th width="33%" class="text-center">Submitted Tasks</th>
                    </tr>
                    
                    <tr>
                      <td>
                      	@foreach($pendingTasks as $pendingTask)
  							          <div class="box box-primary">
    				                <div class="text-center box-header">
    				                  <i class="fa fa-tasks"></i>
    				                  <h3 class="box-title">
    				                  	@php
    				                  		$item = App\Item::where('id', $pendingTask->item_id)->first();
    				                  	@endphp
    				                  	{{ $item->name }}
    				                  </h3>
    				                  
    				                </div>
  				                
    				                <div class="box-body">
    				                  	<div class="callout callout-danger">
    				                  		<p><strong>Start Date: </strong>{{ $pendingTask->start_date }}</p>
    				                  		<p><strong>End Date: </strong>{{ $pendingTask->end_date }}</p>
    				                  		<p><strong>Words: </strong>{{ $pendingTask->word_counts }}</p>
    					                    <p>{{ $pendingTask->description }}</p>
    					                    
    				                  	</div>
    				                </div>
    				                <div class="text-center box-footer clearfix">
    				                	<form action="{{ route('user.pending_status_change') }}" method="post">
    				                		<input type="hidden" name="task_id" value="{{ $pendingTask->id }}">
    				                		<input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
    					                	<button type="submit" class="btn btn-danger" name="decline" value="decline">Decline <i class="fa fa-times"></i></button>

    					                	<button type="submit" class="btn btn-success" name="accept" value="accept">Accept <i class="fa fa-arrow-circle-right"></i></button>
    					                	{{ csrf_field() }}
    					                </form>
    					              </div>
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
                                @php
                                $status = App\ItemSubmission::where('task_id', $submittedTask->id)->orderBy('id', 'desc')->first();
                                @endphp
                                @if($status->manager_rivision == 0)
                                <div class="callout callout-info">
                                @else
                                <div class="callout callout-success">
                                @endif
                                  <p><strong>Submitte Date: </strong>{{ $submittedTask->start_date }}</p>
                                  <p><strong>End Date: </strong>{{ $submittedTask->end_date }}</p>
                                  <p><strong>Words: </strong>{{ $submittedTask->word_counts }}</p>
                                  <p><strong>Submitted Date: </strong>{{ $submittedTask->submission_date }}</p>
                                
                                  <p><strong>Revision By: </strong>
                                  @if($status->manager_rivision == 0)
                                  Manager
                                  @else
                                  Admin
                                  @endif
                                  </p>
                                  
                                </div>
                            </div>
                          </div>
                        @endforeach
                      </td>
                      
                    </tr>
             		
                  </table>
                </div><!-- /.box-body -->

                
               
              </div><!-- /.box -->

              <!-- <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Condensed Full Width Table</h3>
                </div>/.box-header
                <div class="box-body no-padding">
                  <table class="table table-condensed">
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>Task</th>
                      <th>Progress</th>
                      <th style="width: 40px">Label</th>
                    </tr>
                    <tr>
                      <td>1.</td>
                      <td>Update software</td>
                      <td>
                        <div class="progress progress-xs">
                          <div class="progress-bar progress-bar-danger" style="width: 55%"></div>
                        </div>
                      </td>
                      <td><span class="badge bg-red">55%</span></td>
                    </tr>
                    <tr>
                      <td>2.</td>
                      <td>Clean database</td>
                      <td>
                        <div class="progress progress-xs">
                          <div class="progress-bar progress-bar-yellow" style="width: 70%"></div>
                        </div>
                      </td>
                      <td><span class="badge bg-yellow">70%</span></td>
                    </tr>
                    <tr>
                      <td>3.</td>
                      <td>Cron job running</td>
                      <td>
                        <div class="progress progress-xs progress-striped active">
                          <div class="progress-bar progress-bar-primary" style="width: 30%"></div>
                        </div>
                      </td>
                      <td><span class="badge bg-light-blue">30%</span></td>
                    </tr>
                    <tr>
                      <td>4.</td>
                      <td>Fix and squish bugs</td>
                      <td>
                        <div class="progress progress-xs progress-striped active">
                          <div class="progress-bar progress-bar-success" style="width: 90%"></div>
                        </div>
                      </td>
                      <td><span class="badge bg-green">90%</span></td>
                    </tr>
                  </table>
                </div>/.box-body
              </div>/.box -->
            </div><!-- /.col -->
            <!-- <div class="col-md-4">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Simple Full Width Table</h3>
                  <div class="box-tools">
                    <ul class="pagination pagination-sm no-margin pull-right">
                      <li><a href="#">&laquo;</a></li>
                      <li><a href="#">1</a></li>
                      <li><a href="#">2</a></li>
                      <li><a href="#">3</a></li>
                      <li><a href="#">&raquo;</a></li>
                    </ul>
                  </div>
                </div>/.box-header
                <div class="box-body no-padding">
                  <table class="table">
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>Task</th>
                      <th>Progress</th>
                      <th style="width: 40px">Label</th>
                    </tr>
                    <tr>
                      <td>1.</td>
                      <td>Update software</td>
                      <td>
                        <div class="progress progress-xs">
                          <div class="progress-bar progress-bar-danger" style="width: 55%"></div>
                        </div>
                      </td>
                      <td><span class="badge bg-red">55%</span></td>
                    </tr>
                    <tr>
                      <td>2.</td>
                      <td>Clean database</td>
                      <td>
                        <div class="progress progress-xs">
                          <div class="progress-bar progress-bar-yellow" style="width: 70%"></div>
                        </div>
                      </td>
                      <td><span class="badge bg-yellow">70%</span></td>
                    </tr>
                    <tr>
                      <td>3.</td>
                      <td>Cron job running</td>
                      <td>
                        <div class="progress progress-xs progress-striped active">
                          <div class="progress-bar progress-bar-primary" style="width: 30%"></div>
                        </div>
                      </td>
                      <td><span class="badge bg-light-blue">30%</span></td>
                    </tr>
                    <tr>
                      <td>4.</td>
                      <td>Fix and squish bugs</td>
                      <td>
                        <div class="progress progress-xs progress-striped active">
                          <div class="progress-bar progress-bar-success" style="width: 90%"></div>
                        </div>
                      </td>
                      <td><span class="badge bg-green">90%</span></td>
                    </tr>
                  </table>
                </div>/.box-body
              </div>/.box
            
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Striped Full Width Table</h3>
                </div>/.box-header
                <div class="box-body no-padding">
                  <table class="table table-striped">
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>Task</th>
                      <th>Progress</th>
                      <th style="width: 40px">Label</th>
                    </tr>
                    <tr>
                      <td>1.</td>
                      <td>Update software</td>
                      <td>
                        <div class="progress progress-xs">
                          <div class="progress-bar progress-bar-danger" style="width: 55%"></div>
                        </div>
                      </td>
                      <td><span class="badge bg-red">55%</span></td>
                    </tr>
                    <tr>
                      <td>2.</td>
                      <td>Clean database</td>
                      <td>
                        <div class="progress progress-xs">
                          <div class="progress-bar progress-bar-yellow" style="width: 70%"></div>
                        </div>
                      </td>
                      <td><span class="badge bg-yellow">70%</span></td>
                    </tr>
                    <tr>
                      <td>3.</td>
                      <td>Cron job running</td>
                      <td>
                        <div class="progress progress-xs progress-striped active">
                          <div class="progress-bar progress-bar-primary" style="width: 30%"></div>
                        </div>
                      </td>
                      <td><span class="badge bg-light-blue">30%</span></td>
                    </tr>
                    <tr>
                      <td>4.</td>
                      <td>Fix and squish bugs</td>
                      <td>
                        <div class="progress progress-xs progress-striped active">
                          <div class="progress-bar progress-bar-success" style="width: 90%"></div>
                        </div>
                      </td>
                      <td><span class="badge bg-green">90%</span></td>
                    </tr>
                  </table>
                </div>/.box-body
              </div>/.box
            </div>/.col -->
          </div><!-- /.row -->
      </section><!-- / .section -->

</section><!-- /.content -->

@endsection