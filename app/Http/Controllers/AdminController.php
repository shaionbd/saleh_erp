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
use App\Type;
use App\Payment;
use App\Withdrawal;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function getTasks(){
        $data['title'] = "Tasks";
        $data['active'] = "tasks";
        $data['types'] = Type::get();
        $data['processArticles'] = Projects::where(['manager_id' => 0, 'process_status' => 1, 'complete_item'=> 0])->get();
        $data['assignedItems'] = Item::where('process_status', 0)->orWhere('process_status', 3)->get();
        $data['assignedArticles'] = Projects::where(['process_status' => 3, 'complete_item'=> 0])->get();
        // $data['writterPendingTasks'] = Task::where('process_status',0)->where('writter_id', '!=', '0')->get();
        // $data['onGoingTasks'] = Task::where(['process_status' => 1, 'manager_id' => Auth::user()->id])->get();
        // $data['submittedTasks'] = Task::where(['process_status'=> 4, 'is_accepted' => 0, 'manager_id' => Auth::user()->id])->get();
        $data['projects'] = Projects::where(['manager_id' => 0, 'complete_item'=> 0, 'process_status'=> 0])->get();
  
        return view('admin.tasks')->with($data);
    }

    public function getPendingProject(Request $request){
        $project_id = $request['project_id'];
        $project_info = Projects::where('id', $project_id)->firstOrFail();
        echo '
              <h3 class="text-center">'.$project_info->name.'</h3>
              <div class="progress">
                <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%">
                    0% complete
                </div>
              </div>
              <div class="row">
                <div class="task-info col-md-12">
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
                    <strong>Client Instruction: 
                     <a href="'.asset('storage/app/public/tasks/'.$project_info->project_summary_file).'" target="_black">'.$project_info->project_summary_file .'</a>
                    </strong>
                    <div class="well">
                        <i>'.$project_info->project_summary.'</i>
                    </div>
                  </p>
                </div>
                <div class="task-info col-md-7">
                  <div class="row">
                    <div class="col-md-12">
                      <form action="'.url("/admin/task/status/change").'" method="post" class="form-inline">
                        <input type="hidden" name="project_id" value="'.$project_info->id.'">
                        <input type="hidden" id="token" name="_token" value="'.csrf_token().'" />
                        <button type="submit" data-toggle="tooltip" data-placement="bottom" title="accept" class="btn btn-success" name="accept" value="accept"><i class="fa fa-check"></i> Accept</button>
                        <button type="submit" data-toggle="tooltip" data-placement="bottom" title="decline" class="btn btn-danger" name="decline" value="decline"><i class="fa fa-times"></i> Decline</button>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            ';
    }

    public function postCreateProject(Request $request){
      $name = $request['name'];
      $type = $request['type'];
      $project_summary = $request['project_summary'];
      $word_count = $request['words'];
      $end_date = date("y-m-d", strtotime($request['date'])).' '.date("H:m:s", strtotime($request['time']));
      $price = $request['price'];

      $project = new Projects();
      $project->name = $name;
      $project->type = $type;
      $project->project_summary = $project_summary;
      $project->word_counts = $word_count;
      $project->end_date = $end_date;
      $project->price = $price;
      $project->process_status = '1';

      if($request->hasFile('project_summary_file')){
          $filename = time()."_".$request->project_summary_file->getClientOriginalName();
          $request->project_summary_file->storeAs('public/tasks', $filename);
          $project->project_summary_file = $filename;
      }

      $project->save();
      return redirect()->route('admin.tasks');
    }

    public function postTaskStatusChange(Request $request){
      $project_id = $request['project_id'];

      if($request['decline'] == 'decline'){
          $project = Projects::where('id', $project_id)
                  ->first();
          $project->process_status = '2';
          $project->save();
      }elseif($request['accept'] == 'accept'){
        $project = Projects::where('id', $project_id)
                  ->first();
        $project->process_status = '1';
        $project->save();
      }

      return redirect()->route('admin.tasks');

    }

    public function getProcessProject(Request $request){
      $project_id = $request['project_id'];
      $project_info = Projects::where('id', $project_id)->firstOrFail();
      $managers = User::where(['role' => 2, 'is_available' => 1])->get();
      $manager_option="";
      $pmanager_id = "";
      $pmanager_name = "";
      foreach($managers as $manager){
        $manager_option .= '<option value="'.$manager->id.'">'.$manager->name.'</option>';
        $pmanager_id .= $manager->id.',';
        $pmanager_name .= $manager->name.',';
      }
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
                <strong>Article Instruction: 
                <a href="'.asset('storage/app/public/tasks/'.$project_info->project_summary_file).'" target="_black">'.$project_info->project_summary_file .'</a>
                </strong>
                <div class="well">
                    <i>'.$project_info->project_summary.'</i>
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
                    <button type="button" onclick="createTasksViewForAdmin()" class="btn btn-primary">Create Tasks</button>
                    <input type="hidden" id="project_id" name="project_id" value="'.$project_id.'" />
                    <input type="hidden" id="pmanager_id" value="'.$pmanager_id.'" />
                    <input type="hidden" id="pmanager_name" value="'.$pmanager_name.'" />
                    <input type="hidden" id="item-create-url" value="'.url("/admin/create/item").'"/> 
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
              <br>
              <label>-------------------- OR --------------------</label>
              <div class="col-md-12 well">
                <p class="text-center" style="font-size: 16px;font-weight: bold">Assign to Manager</p>
                <form action="'.url("/admin/project/assign/to/manager").'" method="post" enctype="multipart/form-data">
                  <div class="form-group">
                    <label for="manager_id">Manager</label>
                    <select class="form-control" name="manager_id">'.
                    $manager_option
                    .'</select>
                  </div>
                  <div class="form-group">
                    <label for="description">Instruction: 
                      <input type="file" name="description_file">
                      <input type="checkbox" name="same_file" value="checked"> Check for same Article Instruction File
                    </label>
                    <textarea class="form-control" name="description"></textarea>
                  </div>

                  <input type="hidden" id="project_id" name="project_id" value="'.$project_id.'" />
                  <input type="hidden" id="token" name="_token" value="'.csrf_token().'" />
                  <button type="submit" class="btn btn-primary">Assign</button>
                </form>
                <script src="'. asset("public/js/tinymce/tinymce.min.js").'"></script>
                <script>tinymce.init({ selector:"textarea" });</script>
              </div>
              
            </div>
          </div>
        ';
    }

    public function postAssignProject(Request $request){
      $same_file = $request['same_file'];
      $project_id = $request['project_id'];
      $manager_id = $request['manager_id'];
      $description = $request['description'];

      $project = Projects::where('id', $project_id)->first();
      $project->description = $description;
      $project->manager_id = $manager_id;
      $project->process_status = '3';

      if($same_file){
        $project->description_file = $project->project_summary_file;
      }else{
        if($request->hasFile('description_file')){
          $filename = time()."_".$request->description_file->getClientOriginalName();
          $request->description_file->storeAs('public/tasks', $filename);
          $project->description_file = $filename;
        }
      }
      $project->save(); 
      return redirect()->route('admin.tasks');    
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
      $manager_ids = $request['manager_id'];

      for($i=0; $i < sizeof($names); $i++){
        $item = new Item();
        $item->project_id = $project_id;
        $item->name = $names[$i];
        
        if(isset($request->description_file[$i])){
          $filename = time()."_".$request->description_file[$i]->getClientOriginalName();
          $request->description_file[$i]->storeAs('public/tasks', $filename);
          $item->description_file = $filename;
        }

        $item->description = $descriptions[$i];
        $item->manager_id = $manager_ids[$i];
        $item->word_counts = $word_counts[$i];
        $item->type = $types[$i];
        $item->start_date = date("Y-m-d H:i:s");
        $item->end_date = $end_dates[$i]. ' '. $end_times[$i];
        $item->price = $prices[$i];
        $item->process_status = '0';
        $item->save();
      }
      $project = Projects::where('id', $project_id)->first();
      $project->complete_item = '0';
      $project->process_status = '2';
      $project->save();

      return redirect()->route('admin.tasks');
    }

    public function postAssignedItem(Request $request){
      $item_id = $request['item_id'];

      $item_info = Item::where('id', $item_id)->firstOrFail();
      $project_info = Projects::where('id', $item_info->project_id)->firstOrFail();
      $managers = User::where(['role' => 2, 'is_available'=>1])->get();
      $types = Type::get();
      $body = '
          <h3 class="text-center">'.$item_info->name.'</h3>
          <div class="progress">';

          if($item_info->process_status == 3){
            $body .= '<div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" style="width:30%">
                20% complete
            </div>';
          }else{
            $body .= '<div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" style="width:30%">
                30% complete
            </div>';
          }

      $body.='</div>
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
              <p><strong>Assign Date: </strong>'.date("M d Y, h:i A", strtotime($item_info->start_date)).'</p>';
              if($item_info->manager_id && $item_info->process_status == 3){
                $user_info = User::where("id", $item_info->manager_id)->first();
                $body .= '<p><strong>Item Rejected By: </strong><span class="text-danger">'.$user_info->name.'</span></p>';
              }else{
                $body .= '<p><strong>Status: </strong><span class="text-info">Pending</span></p>';
              }
            $body .= '</div>';
            if($item_info->manager_id && $item_info->process_status == 3){
            $body .= '<hr />
            <div class="col-md-8 col-md-offset-2 well">
              <form action="'.url("/admin/task/re-assign/send").'" method="post">
                <h3 class="text-center">Re-Assign Item</h3>
                <div class="form-group">
                  <label>Name</label>
                  <input type="text" name="name" class="form-control" value="'.$item_info->name.'" readonly>
                </div>
                <div class="form-group">
                  <label>Article Name</label>
                  <input type="text" name="project_name" class="form-control" value="'.$project_info->name.'" readonly>
                  <input type="hidden" name="project_id" value="'.$project_info->id.'">
                </div>
                <div class="form-group">
                  <label>Name</label>
                  <input type="number" name="word_count" class="form-control" value="'.$item_info->word_counts.'" readonly>
                </div>
                <div class="form-group">
                  <label>Manager</label>
                  <select class="form-control select2" name="manager_id" style="width: 100%;">';
                    foreach ($managers as $manager) {
                      $body .= '<option value="'.$manager->id.'">'.$manager->name.'</option>';
                    }
                $body .= '</select>
                </div>
                <div class="form-group">
                  <label>Type</label>
                  <select name="type" class="form-control">';
                      foreach($types as $type){
                        $selected = ($item_info->type == $type->name)? "selected":"";
                        $body .= '<option value="'.$type->name.'"'. $selected .' >'.$type->name.'</option>';
                      }
                    
                $body.='</select>
                </div>
                <div class="form-group">
                  <label for="description">Instruction: 
                    <input type="file" name="description_file">
                    <input type="checkbox" name="same_file" value="checked" checked> Check for same Item Instruction File
                  </label>
                  <textarea class="form-control" name="description">'.$item_info->description.'</textarea>
                </div>
                <div class="form-group">
                  <label for="end_date">End Date</label>
                  <input type="date" name="end_date" class="form-control" value="'.date("Y-m-d", strtotime($item_info->end_date)).'">
                </div>
                <div class="form-group">
                  <label for="end_time">End Time</label>
                  <input type="time" name="end_time" class="form-control" value="'.date("H:m", strtotime($item_info->end_date)).'">
                </div>

                <input type="hidden" name="item_id" value="'.$item_id.'">
                <input type="hidden" name="_token" value="'.csrf_token().'" />
                <input type="submit" value="Re Assign" class="btn btn-success" />
              </form>
              <script src="'. asset("public/js/tinymce/tinymce.min.js").'"></script>
              <script>tinymce.init({ selector:"textarea" });</script>
            </div>';
            }
          $body .='</div>

        ';
        echo $body;
    }

    public function postReAssignedItem(Request $request){
      $item_id = $request['item_id'];
      $item = Item::where('id', $item_id)->first();

      $same_file = $request['same_file'];
      
      if($same_file){
        $item->description_file = $item->description_file;
      }else{
        if(isset($request->description_file)){
          $filename = time()."_".$request->description_file->getClientOriginalName();
          $request->description_file->storeAs('public/tasks', $filename);
          $item->description_file = $filename;
        }
      }
      
      $item->description = $request['description'];
      $item->manager_id = $request['manager_id'];
      $item->type = $request['type'];
      $item->start_date = date("Y-m-d H:i:s");
      $item->end_date = $request['end_date']. ' '. $request['end_times'];
      $item->process_status = '0';
      $item->save();

      return redirect()->route('admin.tasks');
    }

    public function postAssignedProject(Request $request){
      $project_id = $request['project_id'];
      $project_info = Projects::where('id', $project_id)->firstOrFail();
      $manager = User::where('id', $project_info->manager_id)->firstOrFail();
      echo '
            <h3 class="text-center">'.$project_info->name.'</h3>
            <div class="progress">
              <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" style="width:30%">
                  30% complete
              </div>
            </div>
            <div class="row">
              <div class="task-info col-md-12">
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
                  <strong>Admin Instruction: 
                    <a href="'.asset('storage/app/public/tasks/'.$project_info->description_file).'" target="_black">'.$project_info->description_file .'</a>
                  </strong>
                  <div class="well">
                      <i>'.$project_info->description.'</i>
                  </div>
                </p>
                <p>
                  <strong>Project Assigned To: </strong> <span class="text-success">'.$manager->name.'</span>
                </p>
              </div>
            </div>
          ';
    }
}
