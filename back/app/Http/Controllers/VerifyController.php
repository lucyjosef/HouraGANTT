<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use App\User;
use Illuminate\Http\Request;

class VerifyController extends Controller
{
    public function verify($token){
        $user = DB::table('users')->where('token', $token)->first();
        return $user;
    }
}
