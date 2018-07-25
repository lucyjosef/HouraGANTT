<?php

namespace App\Http\Controllers;

use App\Resource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\ResourcesResource;

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
}
