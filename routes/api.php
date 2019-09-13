<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
// Route::get('auth/personal', 'Auth\LoginController@personal');
Route::post('register', 'PassportController@register');
Route::post('login', 'PassportController@login');


   Route::post('user/register','Api\LoginController@register');
   Route::post('user/code','IdentifyingCodeController@getCode');
   // Route::post('user/register','IdentifyingCodeController@register');
   Route::post('wx/wx_login','WxController@getWxUserInfo');
   Route::post('wx/setInfo','WxController@setWxUserInfo');
   // 请求微信统一下单接口
   Route::get('payment/place_order', 'PayController@place_order');
   // 请求微信接口, 查看订单支付状态
   Route::get('payment/paid', 'PayController@paid');
   // 微信支付
   Route::post('wxpay', 'PayController@wxpay');
   // 支付成功回刁函数
   Route::post('payment/notify', 'PayController@notify');
   
 






   Route::any('test', 'TestController@test');  
   Route::post('getTask', 'TestController@getTask'); 
   Route::post('enterLove', 'TestController@enterLove'); 
   Route::post('testEnter', 'TestController@testEnter'); 
   Route::post('getTitle', 'TestController@getTitle'); 
   Route::post('t', 'TestController@t'); 
   Route::post('getName', 'TestController@getName');
   Route::post('test/upload', 'TestController@upload');
  
   // 组,无令牌将不能入内  
Route::group(['middleware' => 'auth:api'], function(){
   // 测试token
   Route::post('needToken', 'TestController@needToken'); 
   // 后台管理系统
   Route::post('get_info', 'PassportController@getUserInfo');
   Route::post('logout', 'PassportController@userLogout');
   Route::post('save_error_logger', 'UserController@saveErrorLogger');
   // 消息栏
   Route::post('message/count', 'PassportController@userCount');
   Route::post('message/init', 'MessageController@getMessage');
   Route::post('message/content', 'MessageController@getMessageContent');
   Route::post('message/has_read', 'MessageController@setMessageRead');
   
});
 
