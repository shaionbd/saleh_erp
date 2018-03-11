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

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    //======================= for both manager and writter ======================//
    public function postUpdateAvailability(Request $request)
    {
        $user_id = $request['userid'];
        $is_available = $request['is_available'];

        $user = User::find($user_id);

        $user->is_available = $is_available;
        $user->save();

        if($is_available)
            echo "Your are now available for new jobs";
        else
            echo "Your are now not available";
    }

    public function getProfile()
    {
        $data['title'] = "Profile";
        $data['active'] = "profile";

        $user_id = Auth::user()->id;
        $data['profile'] = User::where('id', $user_id)->firstOrFail();

        return view('profile')->with($data);
    }

    public function updateProfile(Request $request)
    {
        $user_id = $request['user_id'];
        $update_profile = User::find($user_id);

        $update_profile->name = $request['name'];
        $update_profile->email = $request['email'];
        $update_profile->phone = $request['phone'];
        $update_profile->website = $request['website'];
        $update_profile->about_me = $request['about_me'];
        $update_profile->skills = $request['skills'];
        $update_profile->experience = $request['experience'];
        $update_profile->address = $request['address'];
        $update_profile->twitter_link = $request['twitter_link'];
        $update_profile->fb_link = $request['fb_link'];
        $update_profile->google_plus_link = $request['google_plus_link'];
        $update_profile->linkedin_link = $request['linkedin_link'];
        $update_profile->github_link = $request['github_link'];

        if($request->hasFile('image'))
        {
            if($update_profile->image != 'default.png')
            {
                Storage::delete('public/profile/'.$update_profile->image);
            }

            $filename = time()."_".$request->image->getClientOriginalName();
            $request->image->storeAs('public/profile', $filename);
            $update_profile->image = $filename;
        }

        $update_profile->save();

        return redirect()->route('user.profile');
    }

    public function getManagerTeam()
    {
        $data['title'] = "Profile";
        $data['active'] = "profile";
        $data['team_members'] = User::where(['supervisor' => Auth::user()->id, 'status' => 1])->get();

        return view('team')->with($data);
    }

    public function getManagerEditTeam($id)
    {
        $edit_manager_team = User::where('id', '=', $id)->first();

        return response()->json($edit_manager_team);
    }

    public function updateManagerTeam(Request $request)
    {
        $team_member_id = $request['team_member_id'];
        $update_team_member = User::find($team_member_id);

        $update_team_member->name = $request['name'];
        $update_team_member->email = $request['email'];
        $update_team_member->phone = $request['phone'];

        $update_team_member->save();

        return redirect()->route('user.managerTeam');
    }

    public function deleteManagerTeam($id)
    {
        $delete_team_member = User::find($id);
        $delete_team_member->status = 0;
        $delete_team_member->save();

        return redirect()->route('user.managerTeam');
    }

    public function getPayments()
    {
        $data['title'] = "Payments";
        $data['active'] = "payments";

        if(Auth::user()->role == 2)
        {
            $data['net'] = Payment::where('manager_id', Auth::user()->id)
                                    ->sum('manager_share');
            $data['penalty'] = Payment::where('manager_id', Auth::user()->id)
                                    ->sum('manager_penalty');
            $data['withdraw'] = Withdrawal::where('user_id', Auth::user()->id)
                                    ->where('request_status', 1)
                                    ->sum('amount');
            $data['payments'] = Payment::where('manager_id', Auth::user()->id)
                                    ->orderBy('id', 'desc')
                                    ->get();
            $data['withdrawals'] = Withdrawal::where('user_id', Auth::user()->id)
                                    ->orderBy('id', 'desc')
                                    ->get();

            return view('payment')->with($data);
        }
        else if(Auth::user()->role == 3)
        {
            $data['net'] = Payment::where('writter_id', Auth::user()->id)
                                    ->sum('writter_share');
            $data['penalty'] = Payment::where('writter_id', Auth::user()->id)
                                    ->sum('writter_penalty');
            $data['withdraw'] = Withdrawal::where('user_id', Auth::user()->id)
                                    ->where('request_status', 1)
                                    ->sum('amount');
            $data['payments'] = Payment::where('writter_id', Auth::user()->id)
                                    ->orderBy('id', 'desc')
                                    ->get();
            $data['withdrawals'] = Withdrawal::where('user_id', Auth::user()->id)
                                    ->orderBy('id', 'desc')
                                    ->get();

            return view('payment')->with($data);
        }

    }

    public function requestPayment(Request $request)
    {
        $request_type = $request['request_type'];
        $account_no = $request['account_no'];
        $amount = $request['amount'];
        $user_id = Auth::user()->id;

        $withdrawal = new Withdrawal();
        $withdrawal->request_type = $request_type;
        $withdrawal->account_no = $account_no;
        $withdrawal->amount = $amount;
        $withdrawal->user_id = $user_id;

        $withdrawal->save();

        return redirect()->route('user.payment');
    }
    //======================= /for both manager and writter ======================//

    //============================= for writter ==============================//
    public function getTasks()
    {
        $data['title'] = "Tasks";
        $data['active'] = "tasks";

        $data['pendingTasks'] = Task::where(['process_status' => 0, 'writter_id' => Auth::user()->id])->get();
        $data['onGoingTasks'] = Task::where(['process_status' => 1, 'writter_id' => Auth::user()->id])->get();
        $data['submittedTasks'] = Task::where(['process_status'=> 4, 'is_accepted' => 0, 'writter_id' => Auth::user()->id])->get();

        return view('writter.tasks')->with($data);
    }

    public function getArchives($type, $month='', $year='')
    {
        if($type == 'current')
        {
            $month = date('m');
            $year = date('Y');
        }
        else if($type == 'prev')
        {
            if($month > 0)
            {
                $month -- ;
                if ($month == 0)
                {
                    $year--;
                    $month = 12;
                }
            }
        }
        else
        {
            if($month <= 12)
            {
                $month ++ ;
                if ($month == 13)
                {
                    $year++;
                    $month = 1;
                }
            }
        }

        if (Auth::user()->role == 2)
        {
            $user_id = 'manager_id';
        }
        else
        {
            $user_id = 'writter_id';
        }

        $archives = Task::select('id',
                            DB::raw("SUM(MONTH(start_date) = $month AND YEAR(start_date) = $year AND process_status = 4) AS assigned_task"),
                            DB::raw("SUM(MONTH(end_date) = $month AND YEAR(end_date) = $year AND process_status = 4 AND is_accepted = 1) AS delivered_task"),
                            DB::raw("(SELECT SUM(payments.writter_share) FROM tasks LEFT JOIN payments ON tasks.id = payments.task_id WHERE MONTH(tasks.submission_date) = $month AND YEAR(tasks.submission_date) = $year AND tasks.process_status = 4 AND tasks.is_accepted = 1 GROUP BY payments.$user_id) AS total_earning"),
                            DB::raw("(SELECT SUM(payments.writter_penalty) FROM tasks LEFT JOIN payments ON tasks.id = payments.task_id WHERE MONTH(tasks.submission_date) = $month AND YEAR(tasks.submission_date) = $year AND tasks.process_status = 4 AND tasks.is_accepted = 1 GROUP BY payments.$user_id) AS total_penalty")
                        )->get();

        $data = [

            'title' => "Archives",
            'active' => "archives",
            'type' => $type,
            'month' => $month,
            'year' => $year,
            'archives' => $archives

        ];

        return view('archive')->with($data);
    }

    public function getArchiveDetails($year_month)
    {
        $data['title'] = "Task Details";
        $data['active'] = "archives";

        $y_m = explode("-", $year_month);
        $data['month'] = $y_m[1];
        $data['year'] = $y_m[0];

        $data['tasks'] = Task::where('start_date', 'like', $year_month.'-%')->get();

        return view('archive_details')->with($data);
    }


    // post form
    public function postTaskStatusChange(Request $request){
        $task_id = $request['task_id'];
        $user_id = $request['user_id'];

        if($request['decline'] == 'decline'){
            $task = Task::where('id', $task_id)
                    ->where('writter_id', $user_id)
                    ->first();
            $task->process_status = '8';
            $task->save();
        }elseif($request['accept'] == 'accept'){
            $task = Task::where('id', $task_id)
                    ->where('writter_id', $user_id)
                    ->first();
            $task->process_status = '1';
            $task->save();
        }

        return redirect()->route('user.tasks');

    }

    public function postTaskRivisionStatusChange(Request $request){
        $task_id = $request['task_id'];
        $user_id = $request['user_id'];


        $task = Task::where('id', $task_id)
                    ->where('writter_id', $user_id)
                    ->first();
        $task->process_status = '1';
        $task->on_revision = '1';
        $task->save();

        return redirect()->route('user.tasks');

    }

    public function postOnGoingTaskSubmit(Request $request){
        $task_id = $request['task_id'];
        $user_id = $request['user_id'];

        if($request->hasFile('submitted_file')){
            $filename = time()."_".$request->submitted_file->getClientOriginalName();
            $request->submitted_file->storeAs('public/tasks', $filename);

            $task = Task::where('id', $task_id)
                    ->where('writter_id', $user_id)
                    ->first();
            $task->process_status = '4';
            $task->save();

            $itemSubmission = new ItemSubmission();
            $itemSubmission->writter_id = $user_id;
            $itemSubmission->task_id = $task_id;
            $itemSubmission->file = $filename;
            try {
                $status = ItemSubmission::where('task_id', $task_id)->orderBy('id', 'desc')->first();
                if($status)
                  $itemSubmission->re_submission_date = $status->re_submission_date;
            }catch(Exception $e){}

            $itemSubmission->save();

        }
        return redirect()->route('user.tasks');
    }

    // get pending task body
    public function getPendingTask(Request $request){
        $task_id = $request['task_id'];

        $task_info = Task::where('id', $task_id)
                ->where('writter_id', Auth::user()->id)
                ->first();
        $item_info = Item::where('id', $task_info->item_id)->firstOrFail();
        $project_info = Projects::where('id', $item_info->project_id)->firstOrFail();

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
                <br>
                <div class="row">
                  <div class="col-md-12">
                    <form action="'.url("/writter/task/status/change").'" method="post">
                      <input type="hidden" name="task_id" value="'.$task_id.'">
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

    // get OnGoing Task body
    public function getOnGoingTask(Request $request){
        $task_id = $request['task_id'];

        $task_info = Task::where('id', $task_id)
                ->where('writter_id', Auth::user()->id)
                ->first();
        $item_info = Item::where('id', $task_info->item_id)->firstOrFail();
        $project_info = Projects::where('id', $item_info->project_id)->firstOrFail();
        $item_submissions = ItemSubmission::where('task_id', $task_id)->get();

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
                <div class="row">
                  <div class="col-md-12 well">
                    <form action="'.url("/writer/time-extend/request").'" method="post">
                      <div class="form-group">
                        <label>Extend Date</label>
                        <input type="date" name="extend_date" class="form-control" required/>
                       </div>
                       <div class="form-group">
                        <label>Extend Time</label>
                        <input type="time" name="extend_time" class="form-control" required/>
                       </div>
                       <input type="hidden" name="task_id" value="'.$task_id.'">
                       <input type="hidden" name="_token" value="'.csrf_token().'" />
                       <button type="submit" class="btn btn-warning">Request for Time Extend</button>
                    </form>
                  </div>
                  <div class="col-md-12 well">
                    <form action="'.url("/writter/task/submit").'" method="post" enctype="multipart/form-data">
                        <div class="form-group text-left">
                          <label for="submit_file">Upload</label>
                          <input type="file" id="submit_file" name="submitted_file" class="form-control" accept=".xlsx,.xls,.doc, .docx,.ppt, .pptx,.txt,.pdf" required>
                          <p class="text-danger">*Upload .xlsx,.xls,.doc, .docx,.ppt, .pptx,.txt,.pdf file</p>
                        </div>
                        <input type="hidden" name="task_id" value="'.$task_id.'">
                        <input type="hidden" name="user_id" value="'.Auth::user()->id.'">
                        <input type="hidden" name="_token" value="'.csrf_token().'" />
                        <button type="submit" class="btn btn-success">Submit</button>
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
          echo $body;
    }

    public function postOnGoingTaskDateExtend(Request $request){
      $extend_date = $request['extend_date'];
      $extend_time = $request['extend_time'];

      $task_id = $request['task_id'];
      $task = Task::where('id', $task_id)
                  ->first();
      $task->extend_date = $extend_date. ' '.$extend_time;
      $task->extend_date_permission = '0';
      $task->save();

      return redirect()->route('user.tasks');
    }

    public function getSubmittedTask(Request $request){
      $task_id = $request['task_id'];

      $task_info = Task::where('id', $task_id)
              ->where('writter_id', Auth::user()->id)
              ->first();
      $item_info = Item::where('id', $task_info->item_id)->firstOrFail();
      $project_info = Projects::where('id', $item_info->project_id)->firstOrFail();
      $item_submissions = ItemSubmission::where('task_id', $task_id)->get();
      $status = ItemSubmission::where('task_id', $task_id)->orderBy('id', 'desc')->first();

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

    public function getReviewTask(Request $request){
      $task_id = $request['task_id'];

      $task_info = Task::where('id', $task_id)
              ->where('writter_id', Auth::user()->id)
              ->first();
      $item_info = Item::where('id', $task_info->item_id)->firstOrFail();
      $project_info = Projects::where('id', $item_info->project_id)->firstOrFail();
      $item_submissions = ItemSubmission::where('task_id', $task_id)->get();
      $status = ItemSubmission::where('task_id', $task_id)->orderBy('id', 'desc')->first();

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
              <p><strong>Assign Date: </strong>'.date("M d Y, h:i A", strtotime($task_info->start_date)).'</p>
              <p><strong>Submission Date: </strong>'.date("M d Y, h:i A", strtotime($task_info->end_date)).'</p>
              <p><strong>Re-submission Date: </strong><span class="text-danger">'.date("M d Y, h:i A", strtotime($status->re_submission_date)).'</span></p>
              <p><strong>Submitted Date: </strong>'.date("M d Y, h:i A", strtotime($task_info->submission_date)).'</p>';
      if($task_info->extend_date && $task_info->extend_date_permission){
        $body .= '
            <p><strong>Extend Date: </strong><span class="text-danger">'.date("M d Y, h:i A", strtotime($task_info->extend_date)).'</span></p>
        ';
      }
      if ($status->admin_rivision == 2) {
        $body .= '<p><strong>Reviewed By: </strong><i class="text-danger">Admin</i></p>';
      }else{
        $body .= '<p><strong>Reviewed By: </strong><i class="text-danger">Manager</i></p>';
      }
      $body .= '<p><strong>Reason: </strong></p>

                <div class="callout callout-danger">';
                  if($status->admin_rivision == 2){
                    $body .= '<p>'.$status->admin_revision_description.'</p>';
                  }else{
                    $body .= '<p>'.$status->manager_revision_description.'</p>';
                  }
        $body .= '
                </div>
                  <br />
                  <a href="'.asset("storage/app/public/tasks/".$status->file) .'" download="'.$status->file.'" class="btn btn-info btn-block">Download Task file</a>
                  <hr />
                  <div class="col-md-4 col-md-offset-4">
                    <form action="'.url("/writter/revision/task/status/change").'" method="post">
                      <input type="hidden" name="task_id" value="'.$task_id.'">
                      <input type="hidden" name="user_id" value="'.Auth::user()->id.'">
                      <input type="hidden" name="_token" value="'.csrf_token().'" />
                      <button type="submit" class="btn btn-danger" name="accept" value="accept">Rivision <i class="fa fa-arrow-circle-right"></i></button>
                    </form>
                  </div>
              </div>

          </div>
        ';
        echo $body;
    }

    //============================= /for writter ==============================//

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
