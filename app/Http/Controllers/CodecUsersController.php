<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Validator;
use Illuminate\Http\Response;
use App\User;
use Illuminate\Support\Facades\Auth;
class CodecUsersController extends Controller
{
 
 function codecSearch (Request $request){
   return DB::table('codec_users')->insertGetId(['name' => '睡觉111']);
}
 
 
    

}
