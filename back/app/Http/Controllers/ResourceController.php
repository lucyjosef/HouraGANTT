<?php

namespace App\Http\Controllers;

use App\Resource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\ResourcesResource;
use App\Http\Resources\ProjectResource;

class ResourceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ResourcesResource::collection(Resource::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request,$project)
    {
        request()->validate([
            'name' => 'required',
        ]);
        $data = $request->all();
        $data['project_id'] = $project;
        $ressource = Resource::create($data);
        return response()->json([
            'status' => 'success',
            'data' => $ressource
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Resource  $resource
     * @return \Illuminate\Http\Response
     */

    public function show($id)
    {
        return new ResourcesResource(Resource::find($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Resource  $resource
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, $project,$id)
    {
        $token = $request->header('Authorization');
        $token = substr($token, 6);
        $token = trim($token);
        $user = getUserInfo($token);
        $user_id = $user->id;
        $checkRight = checkRight($user_id,$project);
        if($checkRight){
            $resource = Resource::find($id);
            $resource->name = $request->name;
            $resource->ratio = $request->ratio;
            $resource->job = $request->job;
            $resource->first_name = $request->first_name;
            $resource->save();
            InsertLog("UpdateRessource",$id,$user_id);
            return response()->json(["message"=> "updated"],200);
        }else{
            return response()->json(["message"=> "Unauthorized delete"],401);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Resource  $resource
     * @return \Illuminate\Http\Response
     */

    public function destroy(Request $request,$project,$id)
    {
        $token = $request->header('Authorization');
        $token = substr($token, 6);
        $token = trim($token);
        $user = getUserInfo($token);
        $user_id = $user->id;
        $checkRight = checkRight($user_id,$project);
        if($checkRight){
            DB::table('resources')->where('id', $id)->delete();
            $data =  DB::table('tasks')
                ->where('resource_id', $id)
                ->update(['resource_id' => Null]);
            InsertLog("deleteRessource",$id,$user_id);
            return response()->json(['message' => 'ressource delete'], 200);
        }else{
            return response()->json(['message' => 'Unauthorized delete'], 401);

        }

    }

    public function resourceDetail($id) {
        $resources = DB::table('resources')->where('project_id', $id)->get();
        $render = [];
        foreach ($resources as $key => $value) {

            $render[$key]['name'] = $value->name;
            $render[$key]['job'] = $value->job;
            $render[$key]['ratio'] = $value->ratio;

            $task = DB::table('tasks')->where('resource_id', $value->id)->get();
            $render[$key]["nb_tasks"] = count($task);
            $nb_hours = 0;
            foreach ($task as $subvalue) {
                $start_end = addDayswithdate($subvalue->starts_at,$subvalue->duration);// return the task end_date
                $workDays = getWorkdays($subvalue->starts_at,$start_end); // return the task workday exclude week-end
                $hourPerday = 7 * $workDays;
                $nb_hours += $hourPerday;
            }
            $render[$key]["nb_hours"] = $nb_hours;
            $render[$key]["total_cost"] = $nb_hours * $value->ratio;
            
        }
        return response()->json($render, 200);
    }
}
