<?php
function getUserInfo($token) {
     $user = \DB::table('users')->where('token', $token)->first();
    return $user;
}

function InsertLog($function_name,$project_id,$user_id){
  $id =  \DB::table('users')->insertGetId(
        ['function_name' => $function_name, 'project_id' => $project_id,'user_id' => $user_id]
    );
  return $id;
}

function checkRight($user_id,$project_id){
     $right = \DB::table('project_user')->where([
         ['user_id', '=', $user_id],
         ['project_id', '=', $project_id],
     ])->first();
     if($right->right_id === 1){
         return true;
     }else{
         return false;
     }
}