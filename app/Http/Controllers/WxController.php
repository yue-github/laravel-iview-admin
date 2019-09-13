<?php

 /**
  * 验证码控制器
  */
namespace App\Http\Controllers;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Iwanli\Wxxcx\Wxxcx;
use Illuminate\Support\Facades\Auth;
class WxController extends Controller
{
    protected $wxxcx;
 
    function __construct(Wxxcx $wxxcx)
    {
        $this->wxxcx = $wxxcx;
    }

    /**
     * 小程序登录获取用户信息
     * @author 晚黎
     * @date   2017-05-27T14:37:08+0800
     * @return [type]                   [description]
     */
    public function getWxUserInfo()
    {
       //code 在小程序端使用 wx.login 获取
        $code = request('code','');
        //encryptedData 和 iv 在小程序端使用 wx.getUserInfo 获取
        // $encryptedData = request('encryptedData', '');
        // $iv = request('iv', '');
        // token获取
        // $user = Auth::user();
        // $token = $user->createToken('MyApp')->accessToken;
        //根据 code 获取用户 session_key 等信息, 返回用户openid 和 session_key
        $userInfo = $this->wxxcx->getLoginInfo($code);
        $openid=$userInfo['openid'];
        // 根据openid查用户信息
        $userInfo=DB::table('users')->where(['remember_token'=>$openid])->get();
        return response()->json(['status'=>200,'info'=>$userInfo,'token'=>$openid]);
        //获取解密后的用户信息
        // return $this->wxxcx->getUserInfo($encryptedData, $iv);
    }
    public function setWxUserInfo(){
      $result=DB::table('users')->insert(['remember_token'=>request('token')]);
      if($result){
        return response()->json(['status'=>200,'result'=>'wx']);
      }
      
    }
   
}
 