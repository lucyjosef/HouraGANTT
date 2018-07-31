<?php

namespace App\Http\Controllers;

use App\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\TasksResource;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        if(checkProjectRight($id, auth()->user()->id)) {
            return TasksResource::collection(Task::all());
        } else {
            return response()->json(['Forbidden', 403]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,$project)
    { 
        if(checkProjectRight($project->id, auth()->user()->id)) {
            request()->validate([
                'text' => 'required',
                'start_date' => 'required',
                'duration' => 'required'
            ]);
            $data = $request->all();
            $data['project_id'] = $project;
            $data['name'] = $request->text;
            $data['starts_at'] = $request->start_date;
            $tasks = Task::create($data);
            return response()->json([
                'status' => 'success',
                'data' => $tasks
            ]);
        } else {
            return response()->json(['Forbidden', 403]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show($project_id, $id)
    {
        if(checkProjectRight($project_id, auth()->user()->id)) {
            return new TasksResource(Task::find($id));
        } else {
            return response()->json(['Forbidden', 403]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $project_id, $id)
    {
        if(checkProjectRight($project_id, auth()->user()->id)) {
            $checkRight = checkRight(auth()->user()->id,$project_id);
            if($checkRight){
                $task = Task::find($id);
                $task->name = $request->text;
                
                $task->starts_at = $request->start_date;
                $task->duration = $request->duration;
                $task->progress = $request->has("progress") ? $request->progress : 0;
                $task->additional_cost = $request->has("additional_cost") ? $request->progress : 0.00;
                $task->save();
                return response()->json([
                    "action"=> "updated"
                ]);
            } else{
                return response()->json(["message"=> "Unauthorized action"],401);
            }
        } else {
            return response()->json(['Forbidden', 403]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */

    public function destroy(Request $request,$project,$id)
    {

        $user_id = auth()->user()->id;
        $checkRight = checkRight($user_id,$project);
        if($checkRight){
            DB::table('tasks')->where('id', $id)->delete(); 

            InsertLog("deleteTask",$id,$user_id);
            return response()->json(null, 204);
        }else{
            return response()->json(["message"=> "Unauthorized action"],401);
        }
    }

    public function ResourceToTask(Request $request,$project)
    {
        $user_id = auth()->user()->id;
        $resource_id = "";
        $functionName = "";
        $checkRight = checkRight($user_id,$project);
        if($checkRight){
            if($request->action === "add"){
                $resource_id =$request->resource_id;
                $functionName ="AddRessourceToTask";
            }elseif($request->action === "remove"){
                $resource_id = NULL;
                $functionName ="RemoveRessourceToTask";
            }

            $data =  DB::table('tasks')
                ->where('id', $request->task_id)
                ->update(['resource_id' => $resource_id]);

            InsertLog($functionName,$request->task_id,$user_id);
            return response()->json([
                'status' => 'success',
                'data' => $data
            ]);
        } else {
            return response()->json(["message"=> "Unauthorized action"],401);
        }
    }
}