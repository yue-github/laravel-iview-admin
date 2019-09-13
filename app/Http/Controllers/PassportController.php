<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use App\User;
class PassportController extends Controller
{
    // use RegistersUsers;
    // public function __construct()
    // {
    //     $this->middleware('guest');
    // }

    /**
     * Get a validator for an incoming registration request.
     *注册
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function register(Request $request)
    {
        $userName = request('userName');
        $hasUserName = DB::table('users')->where('name',$userName)->get();
        if(!$hasUserName->isEmpty()){
            return response()->json(['status'=>503,'msg'=>'用户名已存在']);
        }
        $validator=Validator::make($request->all(), [
            'userName' => 'required',
            // 'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required'
        ]);
        $user=$this->create($request->all());
        $token=$user->createToken('myApp')->accessToken;
        // 注册管理员身份
        $access=request('access');
        $avatar=request('avatar');
        $result=DB::table('users')
        ->where(['name'=>$user->name])
        ->update(
            [
            'avatar'=>$avatar,
            'user_id'=>$user->id,
            'access'=>$access,
            'count'=>0
            ]
        );
        // 将权限字符串分
        $access=strlen($access)>8?explode("&",$access):array($access);
        if($result){
            return response()
            ->json(
                [
                    'status'=>200,
                    'token'=>$token,
                    'name'=>$user->name,
                    'user_id'=>$user->id,
                    'access'=>$access,
                    'avatar'=>$avatar
                ]
            );
        }
    }
    // 登录
    public function login(){
        $userName=request('userName');
        if(Auth::attempt(['name' => $userName, 'password' => request('password')])){
            $user = Auth::user();
            $token = $user->createToken('MyApp')->accessToken;
            $userMsg = json_decode(DB::table('users')->where('name',$userName)->get())[0];
            $access = $userMsg->access;
            $access = explode("&", $access);
            return response()
            ->json(
                [
                    'status'=>200,
                    'token'=>$token,
                    'name'=>$userMsg->name,
                    'user_id'=>$userMsg->id,
                    'access'=>$access,
                    'avatar'=>$userMsg->avatar
                ]
            );
        }
        else{
            return response()->json(['error'=>'Unauthorised'], 401);
        }
    }
    // 获取用户信息
    public function getUserInfo(){
         $result = Auth::user();
         $result['access'] = explode('&', Auth::user()['access']);
         return $result;
    }
    // 退出登录
    public function userLogout(){
        return response()->json(['status'=>200,'msg'=>'退出成功']);
    } 
    // 用户信息数
    public function userCount(){
        $user=Auth::user();
        return $user->count;
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
   protected function create(array $data)
    {
        return User::create([
            'name' => $data['userName'],
            // 'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }
}