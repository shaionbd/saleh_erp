<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Task;
use App\Projects;
use App\Item;
use App\ItemSubmission;
use App\Payment;
use App\Withdrawal;
use Illuminate\Support\Facades\Storage;

class ManagerController extends Controller
{
  public function getPendingProject(Request $request){
    $project_id = $request['project_id'];
    $project_info = Projects::where('id', $project_id)->firstOrFail();
    echo '
          <h3 class="text-center">'.$project_info->name.'</h3>
          <div class="progress">
            <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100" style="width:10%">
                10% complete
            </div>
          </div>
          <div class="row">
            <div class="task-info col-md-5">
              <div class="row">
                <div class="col-md-4">
                  <p><strong>Tracing ID: </strong>'.$project_info->id.'</p>
                  <p><strong>Project: </strong>'.$project_info->name.'</p>
                </div>
                <div class="col-md-4">
                  <p><strong>Admin Assign Date: </strong>'.date("M d Y, h:i A", strtotime($project_info->start_date)).'</p>
                  <p><strong>Submission Date: </strong>'.date("M d Y, h:i A", strtotime($project_info->end_date)).'</p>
                </div>
                <div class="col-md-4">
                  <p><strong>Estimate Earning: </strong>  ৳'.$project_info->price.'</p>
                  <p><strong>Word Count: </strong>'.$project_info->word_counts.'</p>
                </div>
              </div>
              <p>
                <strong>Admin Instruction: </strong>
                <div class="well">
                    <i>'.$project_info->description.'</i>
                </div>
              </p>
            </div>
            <div class="task-info col-md-7">
              <div class="row">
                <div class="col-md-12">
                  <div class="form-inline">
                    <div class="form-group">
                      <label for="total_tasks" class="sr-only">Total Tasks</label>
                      <input type="number" class="form-control" id="total_tasks" min="1" value="1">
                    </div>
                    <button type="button" onclick="createTasksView()" class="btn btn-primary">Create Tasks</button>
                    <input type="hidden" id="project_id" name="project_id" value="'.$project_id.'" />
                    <input type="hidden" id="item-create-url" value="'.url("/manager/create/item").'"/> 
                    <input type="hidden" id="token" name="_token" value="'.csrf_token().'" />
                  </div>
                </div>
              </div>
              <br>
              <div class="row">
                <div class="col-md-12">
                  <div id="task-create">
                  </div>
                </div>
              </div>
            </div>
          </div>
        ';
  }
  public function getProjects(){
    $data['title'] = "Projects";
    $data['active'] = "projects";

    $data['projects'] = Projects::where(['manager_id' => Auth::user()->id, 'complete_item'=> 0])->get();

    return view('manager.projects')->with($data);
  }

  public function makeProjectComplete($id){
    $project = Projects::where('id', $id)->first();
    $project->complete_item = '1';
    $project->save();
    return redirect()->route('manager.projects');
  }

  public function postCreateItem(Request $request){
    $project_id = $request['project_id'];
    $names = $request['name'];
    $word_counts = $request['word_counts'];
    $types = $request['type'];
    $end_dates = $request['end_date'];
    $end_times = $request['end_time'];
    $prices = $request['price'];
    $descriptions = $request['description'];

    for($i=0; $i < sizeof($names); $i++){
      $item = new Item();
      $item->project_id = $project_id;
      $item->name = $names[$i];
      $item->description = $descriptions[$i];
      $item->manager_id = Auth::user()->id;
      $item->word_counts = $word_counts[$i];
      $item->type = $types[$i];
      $item->start_date = date("Y-m-d H:i:s");
      $item->end_date = $end_dates[$i]. ' '. $end_times[$i];
      $item->price = $prices[$i];
      $item->process_status = '1';
      $item->save();

      $task = new Task();
      $task->item_id = $item->id;
      $task->manager_id = Auth::user()->id;
      $task->word_counts = $word_counts[$i];
      $task->save();
    }
    $project = Projects::where('id', $project_id)->first();
    $project->complete_item = '1';
    $project->save();

    return redirect()->route('manager.tasks');
  }

  public function getTasks(){
      $data['title'] = "Items";
      $data['active'] = "tasks";

      $data['pendingItems'] = Item::where(['process_status' => 0, 'manager_id' => Auth::user()->id])->get();
      $data['assignTasks'] = Task::where('process_status', 8)->orWhere('writter_id', 0)->get();
      $data['writterPendingTasks'] = Task::where('process_status',0)->where('writter_id', '!=', '0')->get();
      $data['onGoingTasks'] = Task::where(['process_status' => 1, 'manager_id' => Auth::user()->id])->get();
      $data['submittedTasks'] = Task::where(['process_status'=> 4, 'is_accepted' => 0, 'manager_id' => Auth::user()->id])->get();
      $data['projects'] = Projects::where(['manager_id' => Auth::user()->id, 'complete_item'=> 0])->get();

      return view('manager.tasks')->with($data);
  }

  // post form
  public function postItemStatusChange(Request $request){
      $item_id = $request['item_id'];
      $user_id = $request['user_id'];

      if($request['decline'] == 'decline'){
          $item = Item::where('id', $item_id)
                  ->where('manager_id', $user_id)
                  ->first();
          $item->process_status = '3';
          $item->save();
      }elseif($request['accept'] == 'accept'){
          $item = Item::where('id', $item_id)
                  ->where('manager_id', $user_id)
                  ->first();
          $item->process_status = '1';
          $item->save();

          $item = Item::where('id', $item_id)
                  ->where('manager_id', $user_id)
                  ->first();
          $task = new Task();
          $task->item_id = $item_id;
          $task->manager_id = Auth::user()->id;
          $task->word_counts = $item->word_counts;
          $task->save();
      }

      return redirect()->route('manager.tasks');
  }

  // post form
  public function postItemSubmissionStatusChange(Request $request){
      $item_submission_id = $request['item_submission_id'];

      if($request['decline'] == 'decline'){
          $reason = $request['reason'];
          $re_submission_date = $request['re_submission_date'];
          $re_submission_time = $request['re_submission_time'];

          $itemSubmission = ItemSubmission::where('id', $item_submission_id)
                  ->first();

          $itemSubmission->manager_revision_description = $reason;
          $itemSubmission->manager_rivision = '2';
          $itemSubmission->re_submission_date = $re_submission_date.' '.$re_submission_time;
          $itemSubmission->save();
      }elseif($request['accept'] == 'accept'){
          $itemSubmission = ItemSubmission::where('id', $item_submission_id)
                ->first();
          $itemSubmission->manager_rivision = '1';
          $itemSubmission->save();
      }

      return redirect()->route('manager.tasks');
  }

  // get pending item body
  public function getPendingItem(Request $request){
      $item_id = $request['item_id'];

      $item_info = Item::where('id', $item_id)->firstOrFail();
      $project_info = Projects::where('id', $item_info->project_id)->firstOrFail();

      echo '
          <h3 class="text-center">'.$item_info->name.'</h3>
          <div class="progress">
            <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width:20%">
                20% complete
            </div>
          </div>
          <div class="row">
            <div class="task-info col-md-8">
              <div class="row">
                <div class="col-md-4">
                  <p><strong>Tracing ID: </strong>'.$item_info->id.'</p>
                  <p><strong>Project: </strong>'.$project_info->name.'</p>
                </div>
                <div class="col-md-4">
                  <p><strong>Article Title: </strong>'.$item_info->name.'</p>
                  <p><strong>Article Type: </strong>'.$item_info->type.'</p>
                </div>
                <div class="col-md-4">
                  <p><strong>Word Count: </strong>'.$item_info->word_counts.'</p>
                </div>
              </div>
              <p>
                <strong>Admin Instruction: 
                <a href="'.asset('storage/app/public/tasks/'.$item_info->description_file).'" target="_black">'.$item_info->description_file .'</a>
                </strong>
                <div class="well">
                    <i>'.$item_info->description.'</i>
                </div>
              </p>
            </div>
            <div class="task-info col-md-4">
              <p><strong>Estimate Earning: </strong>  ৳'.$item_info->price.'</p>
              <p><strong>Assign Date: </strong>'.date("M d Y, h:i A", strtotime($item_info->start_date)).'</p>
              <p><strong>Submission Date: </strong>'.date("M d Y, h:i A", strtotime($item_info->end_date)).'</p>
              <p><strong>Time Tracker: </strong><span id="tracker"></span></p>
              <div class="row">
                <div class="col-md-12">
                  <div class="btn-group btn-group-justified">
                    <div class="btn-group">
                      <button id="remaining-days" type="button" class="btn btn-danger"></button>
                    </div>
                    <div class="btn-group">
                      <button id="remaining-hours" type="button" class="btn btn-primary"></button>
                    </div>
                    <div class="btn-group">
                      <button id="remaining-minutes" type="button" class="btn btn-primary"></button>
                    </div>
                    <div class="btn-group">
                      <button id="remaining-sec" type="button" class="btn btn-primary"></button>
                    </div>
                    <input id="end_date" value="'.date("M d, Y H:i:s", strtotime($item_info->end_date)).'" type="hidden" />
                  </div>
                </div>
              </div>
              <br>
              <div class="row">
                <div class="col-md-12">
                  <form action="'.url("/manager/item/status/change").'" method="post">
                    <input type="hidden" name="item_id" value="'.$item_id.'">
                    <input type="hidden" name="user_id" value="'.Auth::user()->id.'">
                    <button type="submit" data-toggle="tooltip" data-placement="bottom" title="accept" class="btn btn-success" name="accept" value="accept"><i class="fa fa-check"></i> Accept</button>
                    <button type="submit" data-toggle="tooltip" data-placement="bottom" title="decline" class="btn btn-danger" name="decline" value="decline"><i class="fa fa-times"></i> Decline</button>
                    <input type="hidden" name="_token" value="'.csrf_token().'"  />
                  </form>
                </div>
              </div>
              <br>
            </div>
          </div>

          <script>
          // Set the date were counting down to
          (function(){
            // Update the count down every 1 second
            var x = setInterval(function() {
              var end_date = document.getElementById("end_date").value;
              var countDownDate = new Date(end_date).getTime();

                // Get todays date and time
                var now = new Date().getTime();

                // Find the distance between now an the count down date
                var distance = countDownDate - now;

                // Time calculations for days, hours, minutes and seconds
                var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                document.getElementById("remaining-days").innerHTML = days + " Days";

                document.getElementById("remaining-hours").innerHTML = hours + " Hours";

                document.getElementById("remaining-minutes").innerHTML = minutes + " Minutes";

                document.getElementById("remaining-sec").innerHTML = seconds + " Sec";

                // If the count down is over, write some text
                if (distance < 0) {
                    clearInterval(x);
                    document.getElementById("remaining-days").innerHTML = "00 Days";

                    document.getElementById("remaining-hours").innerHTML = "00 Hours";

                    document.getElementById("remaining-minutes").innerHTML = "00 Minutes";

                    document.getElementById("remaining-sec").innerHTML = "00 Sec";
                }
            }, 1000);
          })();

          </script>

        ';
  }

  public function getAssignTask(Request $request){
    $task_id = $request['task_id'];

    $task_info = Task::where('id', $task_id)
            ->first();
    $item_info = Item::where('id', $task_info->item_id)->firstOrFail();
    $project_info = Projects::where('id', $item_info->project_id)->firstOrFail();
    $item_submissions = ItemSubmission::where('task_id', $task_id)->get();
    $writters = User::where('supervisor', Auth::user()->id)->get();
    $body = '
        <h3 class="text-center">'.$item_info->name.'</h3>
        <div class="progress">
          <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" style="width:30%">
              30% complete
          </div>
        </div>
        <div class="row">
          <div class="task-info col-md-8">
            <div class="row">
              <div class="col-md-4">
                <p><strong>Tracing ID: </strong>'.$item_info->id.'</p>
                <p><strong>Project: </strong>'.$project_info->name.'</p>
              </div>
              <div class="col-md-4">
                <p><strong>Article Title: </strong>'.$item_info->name.'</p>
                <p><strong>Article Type: </strong>'.$item_info->type.'</p>
              </div>
              <div class="col-md-4">
                <p><strong>Word Count: </strong>'.$item_info->word_counts.'</p>
                <p><strong>Manager: </strong>'.User::where('id', $item_info->manager_id)->firstOrFail()->name.'</p>
              </div>
            </div>
            <p>
              <strong>Admin Instruction: </strong>
              <div class="well">
                  <i>'.$project_info->description.'</i>
              </div>
            </p>
          </div>
          <div class="task-info col-md-4">
            <p><strong>Estimate Earning: </strong>  ৳'.$item_info->price.'</p>
            <p><strong>Assign Date: </strong>'.date("M d Y, h:i A", strtotime($task_info->start_date)).'</p>';
            if($task_info->writter_id && $task_info->process_status == 8){
              $user_info = User::where("id", $task_info->writter_id)->first();
              $body .= '<p><strong>Item Rejected By: </strong><span class="text-danger">'.$user_info->name.'</span></p>';
            }
          $body .= '</div>
          <hr />
          <div class="col-md-8 col-md-offset-2 well">
            <form action="'.url("/manager/task/assign/send").'" method="post">
              <h3 class="text-center">Assign Task</h3>
              <div class="form-group">
                <label>Writter</label>
                <select class="form-control select2" name="writter_id" style="width: 100%;">';
                  foreach ($writters as $writter) {
                    $body .= '<option value="'.$writter->id.'">'.$writter->name.'</option>';
                  }
              $body .= '</select>
              </div>
              <div class="form-group">
                <label>Instruction</label>
                <textarea class="form-control" rows="5" name="description" required></textarea>
              </div>
              <div class="form-group">
                <label>Submission Date</label>
                <input type="date" name="end_date" class="form-control" required/>
               </div>
               <div class="form-group">
                <label>Submission Time</label>
                <input type="time" name="end_time" class="form-control" required/>
               </div>
               <input type="hidden" name="task_id" value="'.$task_id.'">
               <input type="hidden" name="user_id" value="'.Auth::user()->id.'">
               <input type="hidden" name="_token" value="'.csrf_token().'" />
               <input type="submit" value="Assign" class="btn btn-success" />
            </form>
          </div>
        </div>

      ';
      echo $body;
  }

  public function postAssignTask(Request $request){
    $task_id = $request['task_id'];
    $user_id = $request['user_id'];
    $writter_id = $request['writter_id'];
    $description = $request['description'];
    $end_date = $request['end_date'];
    $end_time = $request['end_time'];

    $task = Task::where('id', $task_id)
            ->first();
    $task->writter_id = $writter_id;
    $task->description = $description;
    $task->end_date = $end_date.' '.$end_time;
    $task->process_status = '0';
    $task->save();

    return redirect()->route('manager.tasks');

  }

  // get pending task body
  public function getWritterPendingTask(Request $request){
      $task_id = $request['task_id'];

      $task_info = Task::where('id', $task_id)
              ->first();
      $item_info = Item::where('id', $task_info->item_id)->firstOrFail();
      $project_info = Projects::where('id', $item_info->project_id)->firstOrFail();

      $user_info = User::where('id', $task_info->writter_id)->first();

      echo '
          <h3 class="text-center">'.$item_info->name.'</h3>
          <div class="progress">
            <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:40%">
                40% complete
            </div>
          </div>
          <div class="row">
            <div class="task-info col-md-8">
              <div class="row">
                <div class="col-md-4">
                  <p><strong>Tracing ID: </strong>'.$item_info->id.'</p>
                  <p><strong>Project: </strong>'.$project_info->name.'</p>
                </div>
                <div class="col-md-4">
                  <p><strong>Article Title: </strong>'.$item_info->name.'</p>
                  <p><strong>Article Type: </strong>'.$item_info->type.'</p>
                </div>
                <div class="col-md-4">
                  <p><strong>Word Count: </strong>'.$item_info->word_counts.'</p>
                  <p><strong>Manager: </strong>'.User::where('id', $item_info->manager_id)->firstOrFail()->name.'</p>
                </div>
              </div>
              <p>
                <strong>Admin Instruction: </strong>
                <div class="well">
                    <i>'.$project_info->description.'</i>
                </div>
              </p>
              <p><strong>Manager Instruction: </strong><br>
                <div class="well">
                  <i>'.$item_info->description.'</i>
                </div>
              </p>
            </div>
            <div class="task-info col-md-4">
              <p><strong>Estimate Earning: </strong>  ৳'.$item_info->price.'</p>
              <p><strong>Assign Date: </strong>'.date("M d Y, h:i A", strtotime($task_info->start_date)).'</p>
              <p><strong>Submission Date: </strong>'.date("M d Y, h:i A", strtotime($task_info->end_date)).'</p>
              <p><strong>Assign To: </strong><span class="text-danger">'.$user_info->name.'</span></p>
              <p><strong>Time Tracker: </strong><span id="tracker"></span></p>
              <div class="row">
                <div class="col-md-12">
                  <div class="btn-group btn-group-justified">
                    <div class="btn-group">
                      <button id="remaining-days" type="button" class="btn btn-danger"></button>
                    </div>
                    <div class="btn-group">
                      <button id="remaining-hours" type="button" class="btn btn-primary"></button>
                    </div>
                    <div class="btn-group">
                      <button id="remaining-minutes" type="button" class="btn btn-primary"></button>
                    </div>
                    <div class="btn-group">
                      <button id="remaining-sec" type="button" class="btn btn-primary"></button>
                    </div>
                    <input id="end_date" value="'.date("M d, Y H:i:s", strtotime($task_info->end_date)).'" type="hidden" />
                  </div>
                </div>
              </div>
            </div>
          </div>

          <script>
          // Set the date were counting down to
          (function(){
            // Update the count down every 1 second
            var x = setInterval(function() {
              var end_date = document.getElementById("end_date").value;
              var countDownDate = new Date(end_date).getTime();

                // Get todays date and time
                var now = new Date().getTime();

                // Find the distance between now an the count down date
                var distance = countDownDate - now;

                // Time calculations for days, hours, minutes and seconds
                var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                document.getElementById("remaining-days").innerHTML = days + " Days";

                document.getElementById("remaining-hours").innerHTML = hours + " Hours";

                document.getElementById("remaining-minutes").innerHTML = minutes + " Minutes";

                document.getElementById("remaining-sec").innerHTML = seconds + " Sec";

                // If the count down is over, write some text
                if (distance < 0) {
                    clearInterval(x);
                    document.getElementById("remaining-days").innerHTML = "00 Days";

                    document.getElementById("remaining-hours").innerHTML = "00 Hours";

                    document.getElementById("remaining-minutes").innerHTML = "00 Minutes";

                    document.getElementById("remaining-sec").innerHTML = "00 Sec";
                }
            }, 1000);
          })();

          </script>

        ';
  }

  // get OnGoing Task body
  public function getOnGoingTask(Request $request){
      $task_id = $request['task_id'];

      $task_info = Task::where('id', $task_id)
              ->first();
      $item_info = Item::where('id', $task_info->item_id)->firstOrFail();
      $project_info = Projects::where('id', $item_info->project_id)->firstOrFail();
      $item_submissions = ItemSubmission::where('task_id', $task_id)->get();
      $user_info = User::where('id', $task_info->writter_id)->first();

      $body = '
          <h3 class="text-center">'.$item_info->name.'</h3>
          <div class="progress">
            <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width:60%">
                60% complete
            </div>
          </div>
          <div class="row">
            <div class="task-info col-md-8">
              <div class="row">
                <div class="col-md-4">
                  <p><strong>Tracing ID: </strong>'.$item_info->id.'</p>
                  <p><strong>Project: </strong>'.$project_info->name.'</p>
                </div>
                <div class="col-md-4">
                  <p><strong>Article Title: </strong>'.$item_info->name.'</p>
                  <p><strong>Article Type: </strong>'.$item_info->type.'</p>
                </div>
                <div class="col-md-4">
                  <p><strong>Word Count: </strong>'.$item_info->word_counts.'</p>
                  <p><strong>Manager: </strong>'.User::where('id', $item_info->manager_id)->firstOrFail()->name.'</p>
                </div>
              </div>
              <p>
                <strong>Admin Instruction: </strong>
                <div class="well">
                    <i>'.$project_info->description.'</i>
                </div>
              </p>
              <p><strong>Manager Instruction: </strong><br>
                <div class="well">
                  <i>'.$item_info->description.'</i>
                </div>
              </p>
              <div class="row">
              <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                      <h3 class="box-title">Item Submission</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body table-responsive no-padding">
                      <table class="table table-hover">
                        <tr>
                          <th>File</th>
                          <th>Review By</th>
                          <th>Reason</th>
                          <th>Submission Date</th>
                          <th>Re-submission Date</th>
                        </tr>';
                        foreach ($item_submissions as $item_submission) {
                          $body .= '<tr>
                            <td><a href="'.asset('storage/app/public/tasks/'.$item_submission->file).'">'.$item_submission->file.'</a></td>';
                            if($item_submission->admin_rivision == 2){
                              $body .= '<td>Admin</td>';
                              $body .= '<td>'.$item_submission->admin_revision_description.'</td>';
                            }else{
                              $body .= '<td>Manager</td>';
                              $body .= '<td>'.$item_submission->manager_revision_description.'</td>';
                            }
                          $body .= '
                            <td>'.date("M d, Y h:i A", strtotime($item_submission->submission_date)).'</td>
                            <td>'.date("M d, Y h:i A", strtotime($item_submission->re_submission_date)).'</td>
                          </tr>';
                        }
                    $body .= '</table>
                    </div><!-- /.box-body -->
                  </div><!-- /.box -->
              </div>
              </div>
            </div>
            <div class="task-info col-md-4">
              <p><strong>Task Done By: </strong><span class="text-danger">'.$user_info->name.'</span></p>
              <p><strong>Estimate Earning: </strong>  ৳'.$item_info->price.'</p>
              <p><strong>Assign Date: </strong>'.date("M d Y, h:i A", strtotime($task_info->start_date)).'</p>
              <p><strong>Submission Date: </strong>'.date("M d Y, h:i A", strtotime($task_info->end_date)).'</p>';
      if($task_info->extend_date && $task_info->extend_date_permission){
        $body .= '
            <p><strong>Extend Date: </strong><span class="text-danger">'.date("M d Y, h:i A", strtotime($task_info->extend_date)).'</span></p>
            <input id="end_date" value="'.date("M d, Y H:i:s", strtotime($task_info->extend_date)).'" type="hidden" />
        ';
      }else {
        $body .= '
            <input id="end_date" value="'.date("M d, Y H:i:s", strtotime($task_info->end_date)).'" type="hidden" />
        ';
      }

      $body .= '
              <p><strong>Time Tracker: </strong><span id="tracker"></span></p>
              <div class="row">
                <div class="col-md-12">
                  <div class="btn-group btn-group-justified">
                    <div class="btn-group">
                      <button id="remaining-days" type="button" class="btn btn-danger"></button>
                    </div>
                    <div class="btn-group">
                      <button id="remaining-hours" type="button" class="btn btn-primary"></button>
                    </div>
                    <div class="btn-group">
                      <button id="remaining-minutes" type="button" class="btn btn-primary"></button>
                    </div>
                    <div class="btn-group">
                      <button id="remaining-sec" type="button" class="btn btn-primary"></button>
                    </div>
                  </div>
                </div>
              </div>
              <br>

            </div>
          </div>

          <script>
          // Set the date were counting down to
          (function(){
            // Update the count down every 1 second
            var x = setInterval(function() {
              var end_date = document.getElementById("end_date").value;
              var countDownDate = new Date(end_date).getTime();

                // Get todays date and time
                var now = new Date().getTime();

                // Find the distance between now an the count down date
                var distance = countDownDate - now;

                // Time calculations for days, hours, minutes and seconds
                var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                document.getElementById("remaining-days").innerHTML = days + " Days";

                document.getElementById("remaining-hours").innerHTML = hours + " Hours";

                document.getElementById("remaining-minutes").innerHTML = minutes + " Minutes";

                document.getElementById("remaining-sec").innerHTML = seconds + " Sec";

                // If the count down is over, write some text
                if (distance < 0) {
                    clearInterval(x);
                    document.getElementById("remaining-days").innerHTML = "00 Days";

                    document.getElementById("remaining-hours").innerHTML = "00 Hours";

                    document.getElementById("remaining-minutes").innerHTML = "00 Minutes";

                    document.getElementById("remaining-sec").innerHTML = "00 Sec";
                }
            }, 1000);
          })();

          </script>

        ';
        echo $body;
  }

  public function getReviewTask(Request $request){
    $task_id = $request['task_id'];

    $task_info = Task::where('id', $task_id)
            ->first();
    $item_info = Item::where('id', $task_info->item_id)->firstOrFail();
    $project_info = Projects::where('id', $item_info->project_id)->firstOrFail();
    $item_submissions = ItemSubmission::where('task_id', $task_id)->get();
    $status = ItemSubmission::where('task_id', $task_id)->orderBy('id', 'desc')->first();
    $user_info = User::where('id', $task_info->writter_id)->first();

    $body = '
        <h3 class="text-center">'.$item_info->name.'</h3>
        <div class="progress">';
        $body .= '<div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width:80%">
              80% complete
          </div>
        </div>
        <div class="row">
          <div class="task-info col-md-8">
            <div class="row">
              <div class="col-md-4">
                <p><strong>Tracing ID: </strong>'.$item_info->id.'</p>
                <p><strong>Project: </strong>'.$project_info->name.'</p>
              </div>
              <div class="col-md-4">
                <p><strong>Article Title: </strong>'.$item_info->name.'</p>
                <p><strong>Article Type: </strong>'.$item_info->type.'</p>
              </div>
              <div class="col-md-4">
                <p><strong>Word Count: </strong>'.$item_info->word_counts.'</p>
                <p><strong>Manager: </strong>'.User::where('id', $item_info->manager_id)->firstOrFail()->name.'</p>
              </div>
            </div>
            <p>
              <strong>Admin Instruction: </strong>
              <div class="well">
                  <i>'.$project_info->description.'</i>
              </div>
            </p>
            <p><strong>Manager Instruction: </strong><br>
              <div class="well">
                <i>'.$item_info->description.'</i>
              </div>
            </p>
            <div class="row">
            <div class="col-md-12">
              <div class="box">
                  <div class="box-header">
                    <h3 class="box-title">Item Submission</h3>
                  </div><!-- /.box-header -->
                  <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                      <tr>
                        <th>File</th>
                        <th>Revision By</th>
                        <th>Reason</th>
                        <th>Submission Date</th>
                        <th>Re-submission Date</th>
                      </tr>';
                      foreach ($item_submissions as $item_submission) {
                        $body .= '<tr>
                          <td><a href="'.asset('storage/app/public/tasks/'.$item_submission->file).'">'.$item_submission->file.'</a></td>';
                          if($item_submission->admin_rivision == 2){
                            $body .= '<td>Admin</td>';
                            $body .= '<td>'.$item_submission->admin_revision_description.'</td>';
                          }else{
                            $body .= '<td>Manager</td>';
                            $body .= '<td>'.$item_submission->manager_revision_description.'</td>';
                          }
                        $body .= '
                          <td>'.date("M d, Y h:i A", strtotime($item_submission->submission_date)).'</td>
                          <td>'.date("M d, Y h:i A", strtotime($item_submission->re_submission_date)).'</td>
                        </tr>';
                      }
                  $body .= '</table>
                  </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div>
            </div>
          </div>
          <div class="task-info col-md-4">
            <p><strong>Estimate Earning: </strong>  ৳'.$item_info->price.'</p>
            <p><strong>Task Done By: </strong><span class="text-danger">'.$user_info->name.'</span></p>
            <p><strong>Assign Date: </strong>'.date("M d Y, h:i A", strtotime($task_info->start_date)).'</p>
            <p><strong>Submission Date: </strong>'.date("M d Y, h:i A", strtotime($task_info->end_date)).'</p>
            <p><strong>Re-submission Date: </strong><span class="text-danger">'.date("M d Y, h:i A", strtotime($status->re_submission_date)).'</span></p>
            <p><strong>Submitted Date: </strong>'.date("M d Y, h:i A", strtotime($task_info->submission_date)).'</p>';
    if($task_info->extend_date && $task_info->extend_date_permission){
      $body .= '
          <p><strong>Extend Date: </strong><span class="text-danger">'.date("M d Y, h:i A", strtotime($task_info->extend_date)).'</span></p>
      ';
    }
    $body .= '
                <br /><br />
                <div class="col-md-12">
                  <a href="'.asset("storage/app/public/tasks/".$status->file) .'" download="'.$status->file.'" class="btn btn-info btn-block">Download Task file</a>
                </div>
                <hr />
                <div class="col-md-12">
                  <form action="'.url("/manager/item/submission/status/change").'" method="post">
                    <input type="hidden" name="item_submission_id" value="'.$status->id.'">
                    <button type="submit" data-toggle="tooltip" data-placement="bottom" title="accept" class="btn btn-success" name="accept" value="accept"><i class="fa fa-check"></i> Accept</button>
                    <input type="hidden" name="_token" value="'.csrf_token().'"  />
                  </form>
                  <form action="'.url("/manager/item/submission/status/change").'" method="post">
                    <label>--------------- OR --------------- </label><br />
                    <div class="form-group">
                      <label>Reason: </label>
                      <textarea name="reason" class="form-control" rows="5" required></textarea>
                    </div>
                    <div class="form-group">
                      <label>Re-submission Date: </label>
                      <input type="date" name="re_submission_date" class="form-control" required>
                    </div>
                    <div class="form-group">
                      <label>Re-submission Time: </label>
                      <input type="time" name="re_submission_time" class="form-control" required>
                    </div>
                    <input type="hidden" name="item_submission_id" value="'.$status->id.'">
                    <button type="submit" data-toggle="tooltip" data-placement="bottom" title="decline" class="btn btn-danger" name="decline" value="decline"><i class="fa fa-times"></i> Decline</button>
                    <input type="hidden" name="_token" value="'.csrf_token().'"  />
                  </form>
                </div>

              </div>
            </div>

        </div>
      ';
      echo $body;
  }

  public function getSubmittedTask(Request $request){
    $task_id = $request['task_id'];

    $task_info = Task::where('id', $task_id)
            ->first();
    $item_info = Item::where('id', $task_info->item_id)->firstOrFail();
    $project_info = Projects::where('id', $item_info->project_id)->firstOrFail();
    $item_submissions = ItemSubmission::where('task_id', $task_id)->get();
    $status = ItemSubmission::where('task_id', $task_id)->orderBy('id', 'desc')->first();
    $user_info = User::where('id', $task_info->writter_id)->first();
    $body = '
        <h3 class="text-center">'.$item_info->name.'</h3>
        <div class="progress">';
        if($status->manager_rivision == 0){
          $body .= '<div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width:80%">
              80% complete
          </div>';
        }else {
          $body .= '<div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100" style="width:90%">
              90% complete
          </div>';
        }
    $body .= '</div>
        <div class="row">
          <div class="task-info col-md-8">
            <div class="row">
              <div class="col-md-4">
                <p><strong>Tracing ID: </strong>'.$item_info->id.'</p>
                <p><strong>Project: </strong>'.$project_info->name.'</p>
              </div>
              <div class="col-md-4">
                <p><strong>Article Title: </strong>'.$item_info->name.'</p>
                <p><strong>Article Type: </strong>'.$item_info->type.'</p>
              </div>
              <div class="col-md-4">
                <p><strong>Word Count: </strong>'.$item_info->word_counts.'</p>
                <p><strong>Manager: </strong>'.User::where('id', $item_info->manager_id)->firstOrFail()->name.'</p>
              </div>
            </div>
            <p>
              <strong>Admin Instruction: </strong>
              <div class="well">
                  <i>'.$project_info->description.'</i>
              </div>
            </p>
            <p><strong>Manager Instruction: </strong><br>
              <div class="well">
                <i>'.$item_info->description.'</i>
              </div>
            </p>
            <div class="row">
            <div class="col-md-12">
              <div class="box">
                  <div class="box-header">
                    <h3 class="box-title">Item Submission</h3>
                  </div><!-- /.box-header -->
                  <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                      <tr>
                        <th>File</th>
                        <th>Revision By</th>
                        <th>Reason</th>
                        <th>Submission Date</th>
                        <th>Re-submission Date</th>
                      </tr>';
                      foreach ($item_submissions as $item_submission) {
                        $body .= '<tr>
                          <td><a href="'.asset('storage/app/public/tasks/'.$item_submission->file).'">'.$item_submission->file.'</a></td>';
                          if($item_submission->admin_rivision == 2){
                            $body .= '<td>Admin</td>';
                            $body .= '<td>'.$item_submission->admin_revision_description.'</td>';
                          }else{
                            $body .= '<td>Manager</td>';
                            $body .= '<td>'.$item_submission->manager_revision_description.'</td>';
                          }
                        $body .= '
                          <td>'.date("M d, Y h:i A", strtotime($item_submission->submission_date)).'</td>
                          <td>'.date("M d, Y h:i A", strtotime($item_submission->re_submission_date)).'</td>
                        </tr>';
                      }
                  $body .= '</table>
                  </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div>
            </div>
          </div>
          <div class="task-info col-md-4">
            <p><strong>Estimate Earning: </strong>  ৳'.$item_info->price.'</p>
            <p><strong>Task Done By: </strong><span class="text-danger">'.$user_info->name.'</span></p>
            <p><strong>Assign Date: </strong>'.date("M d Y, h:i A", strtotime($task_info->start_date)).'</p>
            <p><strong>Submission Date: </strong>'.date("M d Y, h:i A", strtotime($task_info->end_date)).'</p>
            <p><strong>Submitted Date: </strong>'.date("M d Y, h:i A", strtotime($task_info->submission_date)).'</p>';
    if($task_info->extend_date && $task_info->extend_date_permission){
      $body .= '
          <p><strong>Extend Date: </strong><span class="text-danger">'.date("M d Y, h:i A", strtotime($task_info->extend_date)).'</span></p>
      ';
    }


    if ($status->manager_rivision == 0 || $status->manager_rivision == 3) {
      $body .= '<p><strong>Review By: </strong><i class="text-danger">Manager</i></p>';
    }else{
      $body .= '<p><strong>Review By: </strong><i class="text-danger">Admin</i></p>';
    }
    $body .= '<p><strong>Price: </strong></p>
            <div class="well">
              <p><strong>Panalty: </strong>৳220.00 </p>
              <p><strong>Earming: </strong>৳4780.00 </p>
            </div>
          </div>
        </div>
      ';
      echo $body;
  }

  public function getRevisionTask(Request $request){
    $task_id = $request['task_id'];

    $task_info = Task::where('id', $task_id)
            ->first();
    $item_info = Item::where('id', $task_info->item_id)->firstOrFail();
    $project_info = Projects::where('id', $item_info->project_id)->firstOrFail();
    $item_submissions = ItemSubmission::where('task_id', $task_id)->get();
    $status = ItemSubmission::where('task_id', $task_id)->orderBy('id', 'desc')->first();
    $user_info = User::where('id', $task_info->writter_id)->first();

    $body = '
        <h3 class="text-center">'.$item_info->name.'</h3>
        <div class="progress">';
        $body .= '<div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width:80%">
              80% complete
          </div>
        </div>
        <div class="row">
          <div class="task-info col-md-8">
            <div class="row">
              <div class="col-md-4">
                <p><strong>Tracing ID: </strong>'.$item_info->id.'</p>
                <p><strong>Project: </strong>'.$project_info->name.'</p>
              </div>
              <div class="col-md-4">
                <p><strong>Article Title: </strong>'.$item_info->name.'</p>
                <p><strong>Article Type: </strong>'.$item_info->type.'</p>
              </div>
              <div class="col-md-4">
                <p><strong>Word Count: </strong>'.$item_info->word_counts.'</p>
                <p><strong>Manager: </strong>'.User::where('id', $item_info->manager_id)->firstOrFail()->name.'</p>
              </div>
            </div>
            <p>
              <strong>Admin Instruction: </strong>
              <div class="well">
                  <i>'.$project_info->description.'</i>
              </div>
            </p>
            <p><strong>Manager Instruction: </strong><br>
              <div class="well">
                <i>'.$item_info->description.'</i>
              </div>
            </p>
            <div class="row">
            <div class="col-md-12">
              <div class="box">
                  <div class="box-header">
                    <h3 class="box-title">Item Submission</h3>
                  </div><!-- /.box-header -->
                  <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                      <tr>
                        <th>File</th>
                        <th>Revision By</th>
                        <th>Reason</th>
                        <th>Submission Date</th>
                        <th>Re-submission Date</th>
                      </tr>';
                      foreach ($item_submissions as $item_submission) {
                        $body .= '<tr>
                          <td><a href="'.asset('storage/app/public/tasks/'.$item_submission->file).'">'.$item_submission->file.'</a></td>';
                          if($item_submission->admin_rivision == 2){
                            $body .= '<td>Admin</td>';
                            $body .= '<td>'.$item_submission->admin_revision_description.'</td>';
                          }else{
                            $body .= '<td>Manager</td>';
                            $body .= '<td>'.$item_submission->manager_revision_description.'</td>';
                          }
                        $body .= '
                          <td>'.date("M d, Y h:i A", strtotime($item_submission->submission_date)).'</td>
                          <td>'.date("M d, Y h:i A", strtotime($item_submission->re_submission_date)).'</td>
                        </tr>';
                      }
                  $body .= '</table>
                  </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div>
            </div>
          </div>
          <div class="task-info col-md-4">
            <p><strong>Estimate Earning: </strong>  ৳'.$item_info->price.'</p>
            <p><strong>Task Done By: </strong><span class="text-danger">'.$user_info->name.'</span></p>
            <p><strong>Assign Date: </strong>'.date("M d Y, h:i A", strtotime($task_info->start_date)).'</p>
            <p><strong>Submission Date: </strong>'.date("M d Y, h:i A", strtotime($task_info->end_date)).'</p>
            <p><strong>Re-submission Date: </strong><span class="text-danger">'.date("M d Y, h:i A", strtotime($status->re_submission_date)).'</span></p>
            <p><strong>Submitted Date: </strong>'.date("M d Y, h:i A", strtotime($task_info->submission_date)).'</p>';
    if($task_info->extend_date && $task_info->extend_date_permission){
      $body .= '
          <p><strong>Extend Date: </strong><span class="text-danger">'.date("M d Y, h:i A", strtotime($task_info->extend_date)).'</span></p>
      ';
    }
    $body .= '
                <br /><br />
                <p><strong>Reason: </strong></p>
                <div class="callout callout-danger">
                  <p>'.$status->admin_revision_description.'</p>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <a href="'.asset("storage/app/public/tasks/".$status->file) .'" download="'.$status->file.'" class="btn btn-info btn-block">Download Task file</a>
                  </div>
                  <hr />
                  <div class="col-md-12">
                    <form action="'.url("/manager/item/submission/status/change").'" method="post">
                      <div class="form-group">
                        <label>Reason: </label>
                        <textarea name="reason" class="form-control" rows="5" required></textarea>
                      </div>
                      <div class="form-group">
                        <label>Re-submission Date: </label>
                        <input type="date" name="re_submission_date" class="form-control" required>
                      </div>
                      <div class="form-group">
                        <label>Re-submission Time: </label>
                        <input type="time" name="re_submission_time" class="form-control" required>
                      </div>
                      <input type="hidden" name="item_submission_id" value="'.$status->id.'">
                      <button type="submit" data-toggle="tooltip" data-placement="bottom" title="resend to writter" class="btn btn-danger" name="decline" value="decline">Resend to Writter<i class="fa fa->arrow"></i></button>
                      <input type="hidden" name="_token" value="'.csrf_token().'"  />
                    </form>
                    <p>------------ OR ----------- </p>
                    <form action="'.url("/manager/item/submission/file").'" method="post" enctype="multipart/form-data">
                      <div class="form-group text-left">
                        <label for="submit_file">Upload</label>
                        <input type="file" id="submit_file" name="submitted_file" class="form-control" accept=".xlsx,.xls,.doc, .docx,.ppt, .pptx,.txt,.pdf" required>
                        <p class="text-danger">*Upload .xlsx,.xls,.doc, .docx,.ppt, .pptx,.txt,.pdf file</p>
                      </div>
                      <input type="hidden" name="item_submission_id" value="'.$status->id.'">
                      <button type="submit" data-toggle="tooltip" data-placement="bottom" title="upload" class="btn btn-danger" name="Upload" value="upload">Upload<i class="fa fa-left->arrow"></i></button>
                      <input type="hidden" name="_token" value="'.csrf_token().'"  />
                    </form>
                  </div>
                </div>


              </div>
            </div>

        </div>
      ';
      echo $body;
  }

  public function postTaskFile(Request $request){
    $item_submission_id = $request['item_submission_id'];

    if($request->hasFile('submitted_file')){
        $filename = time()."_".$request->submitted_file->getClientOriginalName();
        $request->submitted_file->storeAs('public/tasks', $filename);

        $status = ItemSubmission::where('id', $item_submission_id)
                ->first();

        $itemSubmission = new ItemSubmission();
        $itemSubmission->writter_id = $status->writter_id;
        $itemSubmission->task_id = $status->task_id;
        $itemSubmission->manager_rivision = $status->manager_rivision;
        $itemSubmission->file = $filename;
        $itemSubmission->save();

    }
    return redirect()->route('manager.tasks');
  }
}
