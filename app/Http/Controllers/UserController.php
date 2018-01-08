<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Task;
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
    public function postUpdateAvailability(Request $request){
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

    public function getProfile(){

        $data['title'] = "Profile";
        $data['active'] = "profile";

        $user_id = Auth::user()->id;
        $data['profile'] = User::where('id', $user_id)->firstOrFail();

        return view('profile')->with($data);
    }

    public function updateProfile(Request $request){

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

        if($request->hasFile('image')){
            if($update_profile->image != 'default.png'){
                Storage::delete('public/profile/'.$update_profile->image);
            }
            $filename = time()."_".$request->image->getClientOriginalName();
            $request->image->storeAs('public/profile', $filename);
            $update_profile->image = $filename;
        }

        $update_profile->save();

       return redirect()->route('user.profile');
    }

    public function getPayments(){
        $data['title'] = "Payments";
        $data['active'] = "payments";

        if(Auth::user()->role == 3){
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
        }else if(Auth::user()->role == 2){
            $data['net'] = Payment::where('manager_id', Auth::user()->id)
                                ->sum('manager_share');
            $data['penalty'] = Payment::where('manager_id', Auth::user()->id)
                                ->sum('manager_penalty');
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

    public function requestPayment(Request $request){
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
    public function getTasks(){
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

        $archives = Task::select('id',
                            DB::raw("SUM(MONTH(start_date) = $month AND YEAR(start_date) = $year AND process_status = 4) AS assigned_task"),
                            DB::raw("SUM(MONTH(end_date) = $month AND YEAR(end_date) = $year AND process_status = 4 AND is_accepted = 1) AS delivered_task"),
                            DB::raw("(SELECT SUM(payments.writter_share) FROM tasks LEFT JOIN payments ON tasks.id = payments.task_id WHERE MONTH(tasks.submission_date) = $month AND YEAR(tasks.submission_date) = $year AND tasks.process_status = 4 AND tasks.is_accepted = 1 GROUP BY payments.writter_id) AS total_earning"),
                            DB::raw("(SELECT SUM(payments.writter_penalty) FROM tasks LEFT JOIN payments ON tasks.id = payments.task_id WHERE MONTH(tasks.submission_date) = $month AND YEAR(tasks.submission_date) = $year AND tasks.process_status = 4 AND tasks.is_accepted = 1 GROUP BY payments.writter_id) AS total_penalty")
                        )->get();
        $data = [

            'title' => "Archives",
            'active' => "archives",
            'type' => $type,
            'month' => $month,
            'year' => $year,
            'archives' => $archives

        ];
        return view('writter.archive')->with($data);
    }

    public function getArchiveDetails($year_month)
    {
        $data['title'] = "Task Details";
        $data['active'] = "archives";

        $y_m = explode("-", $year_month);
        $data['month'] = $y_m[1];
        $data['year'] = $y_m[0];

        $data['tasks'] = Task::where('start_date', 'like', $year_month.'-%')->get();

        return view('writter.archive_details')->with($data);
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
                $itemSubmission->re_submission_date = $status->re_submission_date;
            }catch(Exception $e){}

            $itemSubmission->save();

        }
        return redirect()->route('user.tasks');
    }

    // get pending task of user
    public function getPendingTask(Request $request){
        $task_id = $request['task_id'];
        echo '
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
          ';
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
