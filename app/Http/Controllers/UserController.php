<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Task;
use App\ItemSubmission;

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

        $update_profile->save();

       return redirect()->route('user.profile');
    }
    //======================= /for both manager and writter ======================//

    //============================= for writter ==============================//
    public function getTasks(){
        $data['title'] = "Tasks";
        $data['active'] = "tasks";

        $data['pendingTasks'] = Task::where('process_status', 0)->get();
        $data['onGoingTasks'] = Task::where('process_status', 1)->get();
        $data['submittedTasks'] = Task::where(['process_status'=> 4, 'is_accepted' => 0])->get();    

        return view('writter.tasks')->with($data);
    }

    public function getArchives(){

    }

    public function getPayments(){

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
