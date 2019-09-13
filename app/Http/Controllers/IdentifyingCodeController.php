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
class IdentifyingCodeController extends Controller
{
    // 聚合平台
      public function getCode(Request $request){

            $code=mt_rand(1000,9999);
            $request->session()->put('code',$code);
            return $code;
            exit();
            $url = "http://v.juhe.cn/sms/send";
            $params = array(
                'key'   => '7a6e33a29ae307c387c5120508f6439f', //您申请的APPKEY
                'mobile'    => request('mobile'), //接受短信的用户手机号码
                'tpl_id'    => '142529', //您申请的短信模板ID，根据实际情况修改
                'tpl_value' =>'#code#='.$code.'&#company#=明正道大学堂' //您设置的模板变量，根据实际情况修改
            );

            $paramstring = http_build_query($params);
            $content = $this->juheCurl($url, $paramstring);
            $result = json_decode($content, true);
            if ($result) {
                return response()->json(['status'=>'200','msg'=>'验证码成功生成']);
            } else {
                return response()->json(['status'=>503,'msg'=>'验证码请求失败']);
            }

            /**
             * 请求接口返回内容
             * @param  string $url [请求的URL地址]
             * @param  string $params [请求的参数]
             * @param  int $ipost [是否采用POST形式]
             * @return  string
             */
            
      }
      
      public function juheCurl($url, $params = false, $ispost = 1)
      {
          $httpInfo = array();
          $ch = curl_init();
          curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
          curl_setopt($ch, CURLOPT_USERAGENT, 'JuheData');
          curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
          curl_setopt($ch, CURLOPT_TIMEOUT, 60);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
          if ($ispost) {
              curl_setopt($ch, CURLOPT_POST, true);
              curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
              curl_setopt($ch, CURLOPT_URL, $url);
          } else {
              if ($params) {
                  curl_setopt($ch, CURLOPT_URL, $url.'?'.$params);
              } else {
                  curl_setopt($ch, CURLOPT_URL, $url);
              }
          }
          $response = curl_exec($ch);
          if ($response === FALSE) {
              //echo "cURL Error: " . curl_error($ch);
              return false;
          }
          $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
          $httpInfo = array_merge($httpInfo, curl_getinfo($ch));
          curl_close($ch);
          return $response;
      }
      
      public function register(Request $request){
            return $request->session()->pull('code');
            exit();
            if(request('code')==$request->session()->pull('code')){
                // $this->endSession();
                return response()->json(['status'=>200,'msg'=>'注册成功']);

            }else{
                return response()->json(['status'=>503,'msg'=>'注册失败']);
            }
      }
}
 