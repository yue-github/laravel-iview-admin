<?php
/**
 * Created by PhpStorm.
 * User: hzh
 * Date: 2018/5/1
 * Time: 14:17
 */
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\ApiController;
// use App\Http\Controllers\Controller;
// use Illuminate\Http\Request;
// use App\User;
// use Illuminate\Support\Facades\Auth;
// use Laravel\Passport\Token;
use Illuminate\Support\Facades\Validator;


class LoginController extends ApiController
{
    protected $successStatus = 200;

    public function __construct()
    {
        $this->middleware('auth:api')->only([
            'logout'
        ]);
    }

    // 登录用户名标示为email字段
    public function username()
    {
        return 'email';
    }

    public function login(Request $request){

        $user = $request->only(['password','email']);
        if(count($user) != 2)return response()->json(['error_code'=>203,'error'=>'Missing Parameter']);
        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){
            $user = Auth::user();
            $success['token'] =  $user->createToken('Imagingbay')->accessToken;
            $success['email'] =  $user->email;
            $success['uid'] =  $user->id;
            return response()->json(['error_code'=>0,'data' => $success], 200)->withHeaders(
                [
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer '.$success['token']
                ]
            );
        }
        else{
            return response()->json(['error_code'=>202,'error'=>'Unauthorised'], 401);
        }
    }

    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        return response()->json(['msg'=>'123']);
        // $validator = Validator::make($request->all(), [
        //     'name' => 'required',
        //     'password' => 'required'
            
        // ]);

        // if ($validator->fails()) {
        //     return response()->json(['error'=>$validator->errors()]);
        // }else{
        //    return response()->json(['error_code'=>0,'data'=>1]); 
        // }

        // $input = $request->all();
        // $input['password'] = bcrypt($input['password']);
        // $user = User::create($input);
        // $success['token'] =  $user->createToken('Imagingbay')->accessToken;
        // // $success['email'] =  $user->email;
        // // $success['uid'] =  $user->id;

        
    }

    // /*
    //  * 注销账号
    //  */
    // public function logout(Request $request)
    // {
    //     'status_code' => 200, 'data' => null]);

    //     $user = $this->guard()->user();
    //     if (empty($user)) {
    //         return response()->json(['message' => '暂未登录', 'error_code' => 20403, 'data' => null]);
    //     }

    //     // 获取当前登陆用户的token并且将其删除
    //     $token = Auth::guard('api')->user()->token();
    //     if (empty($token)) {
    //         return response()->json(['message' => '暂无有效令牌', 'error_code' => 20403, 'data' => null]);
    //     }

    //     if (!empty($token->delete())) {
    //         return response()->json(['message' => '退出成功', 'error_code' => 0, 'data' => null]);
    //     } else {
    //         return response()->json(['message' => '退出失败', 'error_code' => 0, 'data' => null]);
    //     }
    // }


    /**
     * details api
     *
      
     */
    // public function getDetails()
    // {
    //     $user = Auth::user();
    //     return response()->json(['success' => $user], $this->successStatus);
    // }

}
 