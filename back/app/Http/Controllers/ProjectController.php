<?php

namespace App\Http\Controllers;

use App\Resource;
use App\Task;
use App\User;
use Exception;
use App\Project;
use App\Mail\Invitation;
use Illuminate\Http\Request;
use App\Mail\InvitationProject;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Http\Resources\ProjectResource;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ProjectResource::collection(Project::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $token = $request->header('Authorization');
        $token = substr($token, 6);
        $token = trim($token);
        $user = getUserInfo($token);
        
        $user_id = $user->id;
        $project = new Project();
        $project->name = $request->name;
        $project->description = $request->description;
        $project->duration_days = $request->duration_days;
        $project->link = $request->link;
        $project->billing = $request->billing;
        $project->save();
 
        // $right_id = DB::table('project_user')->where([
        //     ['user_id', '=', $request->user_id],
        //     ['project_id', '=', $project->id],
        // ])->get();

        DB::table('project_user')->insert(
            [
                'user_id' => $user_id,
                'project_id' => $project->id
            ]
        );

        return $project;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return new ProjectResource(Project::find($id));
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
        DB::table('projects')->where('id', $id)->update($request[0]);
        return response()->json([$request[0], 200]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        DB::table('projects')->where('id', $id)->delete();
        return response()->json(null, 204);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function sendInvitation(Request $request, $id)
    {
        $project = Project::findOrFail($id);
        $project->temp_username = $request->user_email;
        $project->temp_password = str_random(10);

        try {
            $user_to_create = [
                'first_name' => $project->temp_username,
                'email' => $request->user_email,
                'password' => $project->temp_password
            ];

            $created_user = User::create($user_to_create);

            DB::table('project_user')->insert(
                [
                    'user_id' => $created_user->id,
                    'project_id' => $id
                ]
            );

            Mail::to($request->user_email)
                ->cc('houragantt-2eebaf@inbox.mailtrap.io')
                ->send(new Invitation($project));

            $message = 'User account created and invitation sent';
        } catch (Exception $e) {

            Mail::to($request->user_email)
                ->cc('houragantt-2eebaf@inbox.mailtrap.io')
                ->send(new InvitationProject($project));

            $message = 'This user has already an account, invitation has been sent';
        }

        return response()->json($message, 200);
    }
    public function billingCost($id){
        $data = Task::where('project_id', $id)
            ->get();
        $billingTotal = 0;
        $billingPerTask = 0;
        foreach ($data as $value) {
            $start_end = addDayswithdate($value->starts_at,$value->duration);// return the task end_date
            $workDays = getWorkdays($value->starts_at,$start_end); // return the task workday exclude week-end
            $hourPerday = 7 * $workDays;
             if($value->additional_cost){
                 $billingPerTask = $billingPerTask+$value->additional_cost; // return the billing when additionalcost is defined
             }
            if($value->resource_id){
                $resource = Resource::find($value->resource_id);
                $rate_explode = explode('.',$resource->ratio);
                $billing = $hourPerday * intval($rate_explode[0]);
                $billingPerTask = $billing + $billingPerTask; // return the billing when resource is defined
            }
            $billingTotal+=$billingPerTask; //return the billing per task
            $billingPerTask =0;
        }
       return $billingTotal; //return the Total billing

    }

}
