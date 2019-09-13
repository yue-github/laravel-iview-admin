<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Validator;

class UsersController extends Controller
{
    public function wx_login(Request $request) {
        $this->validate($request, [
            'code' => 'required'
        ]);
            
        $code = $request->input('code');
        $open_id = \App\Utils\WxLoginUtil::getOpenid($code);
        
        $result = DB::table('users')->where('open_id', $open_id)->first();
        
        if ($result == null) {
            $result = DB::table('instructor')->where('open_id', $open_id)->first();
            if ($result == null) {
                return $this->fails();
            } else {
                $instructor_id = $result->id;
                $data = ["id"=>$instructor_id];
                $token = Crypt::encrypt(json_encode($data).'.'.time());
                $data['token'] = $token;
                $data['is_user'] = 0;
                return $this->success($data);
            }

        } else {
            $user_id = $result->id;
            $data = ["id"=>$user_id];
            $token = Crypt::encrypt(json_encode($data).'.'.time());
            $data['token'] = $token;
            $data['is_user'] = 1;
            return $this->success($data);
        }       
    }
    public function bind_user(Request $request) 
    {
        $result = \App\Utils\WxLoginUtil::bind_user($request);
        return $this->success($result);
    }
    public function bind_instructor(Request $request) 
    {
        $result = \App\Utils\WxLoginUtil::bind_instructor($request);
        return $this->success($result);
    }
    public function batchRechargeByUserId(Request $request) 
    {
        $result = \App\Utils\UserUtil::batchRechargeByUserId($request);
        return $this->success($result);
    }
    public function batchRechargeByIdcard(Request $request) 
    {
        $result = \App\Utils\UserUtil::batchRechargeByIdcard($request);
        return $this->success($result);
    }
    public function login(Request $request) {
    
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string|max:11',
            'password' => 'required|string',
            'shop_id' => 'integer|min:0',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $user = DB::table("users")
            ->where("phone", "=", $request->input('phone'))
            ->where("password", "=", md5($request->input('password')))
            ->where("shop_id", "=", $request->input('shop_id'))
            ->first();
        if (!$user) {
            return $this->fails('用户名或密码错误');
        }

        $data = ['id'=>$user->id];

        $shop = DB::table("shops")->where('owner_id', $user->id)->first();
        if($shop) {
            $data['shop_id'] = $shop->id;
        }

        $token = Crypt::encrypt(json_encode($data).'.'.time());

        $data['token'] = $token;

        return $this->success($data);
    }
    public function login_by_idcard(Request $request) {
    
        $validator = Validator::make($request->all(), [
            'idcard' => 'string|max:18',
            'password' => 'required|string',
            'shop_id' => 'integer|min:0',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $user = DB::table("users")
            ->where("idcard", "=", $request->input('idcard'))
            ->where("password", "=", md5($request->input('password')))
            ->where("shop_id", "=", $request->input('shop_id'))
            ->first();
        if (!$user) {
            return $this->fails('用户名或密码错误');
        }

        $data = ['id'=>$user->id];

        $shop = DB::table("shops")->where('owner_id', $user->id)->first();
        if($shop) {
            $data['shop_id'] = $shop->id;
        }

        $token = Crypt::encrypt(json_encode($data).'.'.time());

        $data['token'] = $token;

        return $this->success($data);
    }
    public function sms_send(Request $request) {
    
        $validator = Validator::make($request->all(), [
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $phone = $request->get("phone");
        $id = \App\Utils\SMSUtil::create($phone);
        return $this->success($id);
    }
    
    public function profile(Request $request) {

        $validator = Validator::make($request->all(), [
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('users')
            ->select([
                'users.id as id',
                'users.name as name',
                'users.idcard as idcard',
                'users.company as company',
                'users.phone as phone',
                'users.email as email',
                'users.balance as balance',
                'users.isLookVideo as isLookVideo',
                'users.open_id as open_id',
            ]);
        $result = $result->where('users.id', '=', $this->token->id);
        $result = $result->first();
        return $this->success($result);
    }
    public function edit(Request $request) {

        $validator = Validator::make($request->all(), [
            'name' => 'string|max:100',
            'idcard' => 'string|max:18',
            'company' => 'string|max:500',
            'email' => 'string|email',
            'open_id' => 'string|max:100',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('users');
        $info = 'usersController->edit: ';
        $result = $result->where('users.id', '=', $this->token->id);
        $info = $info . 'with: {'; 
        if ($request->has('id')) 
            $info = $info . 'id => ' . $request->input('id') . ', '; 
        $info = $info . 'id => ' . $request->input('token.id') . ', ';
        $info = $info . "}, ";
        $data =[];
        $info = $info . 'data: {';
        if ($request->has('name')){
            $data["name"] = $request->input('name');
            $info = $info . 'name => ' . $request->input('name') . ', ';
        }
        if ($request->has('idcard')){
            $data["idcard"] = $request->input('idcard');
            $info = $info . 'idcard => ' . $request->input('idcard') . ', ';
        }
        if ($request->has('company')){
            $data["company"] = $request->input('company');
            $info = $info . 'company => ' . $request->input('company') . ', ';
        }
        if ($request->has('email')){
            $data["email"] = $request->input('email');
            $info = $info . 'email => ' . $request->input('email') . ', ';
        }
        if ($request->has('open_id')){
            $data["open_id"] = $request->input('open_id');
            $info = $info . 'open_id => ' . $request->input('open_id') . ', ';
        }
        $data["updated_at"] = date('Y-m-d H:i:s');
        $info = $info . 'updated_at => ' . date('Y-m-d H:i:s') . ', ';
        $info = $info . "}";
        Log::info($info);
        $result->update($data);
        return $this->success();
    }
    public function shop_edit(Request $request) {

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|min:0',
            'name' => 'string|max:100',
            'phone' => 'required|string|max:11',
            'idcard' => 'string|max:18',
            'company' => 'string|max:500',
            'email' => 'string|email',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('users');
        $info = 'usersController->shop_edit: ';
        if ($request->has('id'))
            $result = $result->where('users.id', '=', $request->input('id'));
        $info = $info . 'with: {'; 
        if ($request->has('id')) 
            $info = $info . 'id => ' . $request->input('id') . ', '; 
        $info = $info . 'id => ' . $request->input('id') . ', ';
        $info = $info . "}, ";
        $data =[];
        $info = $info . 'data: {';
        if ($request->has('name')){
            $data["name"] = $request->input('name');
            $info = $info . 'name => ' . $request->input('name') . ', ';
        }
        if ($request->has('phone')){
            $data["phone"] = $request->input('phone');
            $info = $info . 'phone => ' . $request->input('phone') . ', ';
        }
        if ($request->has('idcard')){
            $data["idcard"] = $request->input('idcard');
            $info = $info . 'idcard => ' . $request->input('idcard') . ', ';
        }
        if ($request->has('company')){
            $data["company"] = $request->input('company');
            $info = $info . 'company => ' . $request->input('company') . ', ';
        }
        if ($request->has('email')){
            $data["email"] = $request->input('email');
            $info = $info . 'email => ' . $request->input('email') . ', ';
        }
        $data["updated_at"] = date('Y-m-d H:i:s');
        $info = $info . 'updated_at => ' . date('Y-m-d H:i:s') . ', ';
        $info = $info . "}";
        Log::info($info);
        $result->update($data);
        return $this->success();
    }
    public function setpswd(Request $request) {
    
        $validator = Validator::make($request->all(), [
            'password' => 'required|string',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $opwd = $request->input('opassword');
        $user = DB::table("users")
            ->where("id", $this->token->id)
            ->where("password", md5($opwd))->first();

        if (!$user) {
            return $this->fails('旧密码不正确');
        }

        DB::table("users")->where("id", $this->token->id)->update([
            'password'=>md5($request->input('password')),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        return $this->success();
    }
    public function set_password(Request $request){
		$validator = Validator::make($request->all(), [
                'password' => 'required',
                'phone' => 'required',
        ]);
		
		if(!\App\Utils\SMSUtil::check($request)){
            return $this->fails('验证码错误');
        }
		
		DB::table("users")
            ->where("phone", $request->input('phone'))
            ->update([
                'password'=>md5($request->input('password')),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
		
		return $this->success();
	}
    public function search_pay_project(Request $request) {

        $validator = Validator::make($request->all(), [
            'offset' => 'required|integer|min:0',
            'length' => 'required|integer|min:1',
            'product_id' => 'required|integer|min:1'
        ]);
        if($validator->fails()) {
            return $this->fails( $validator->errors()->all());
        }
        //该产品班级里已有的成员
        $class_user_id_arr = DB::table('class_users')
                ->leftJoin('class', 'class_users.class_id', '=', 'class.id')
                ->where('class.product_id', $request->input('product_id'))
                ->lists('class_users.user_id');

        $result = DB::table('purchased')
            ->select(['purchased.id as purchased_id',
                      'purchased.product_id as product_id',
                      'orders.owner_id as user_id',
                      'users.id as user_id',
                      'users.name as user_name',
                      'users.company as user_company',
                      'users.phone as user_phone',
                      'users.email as user_email'])
            ->leftJoin('orders', 'orders.id', '=', 'purchased.order_id')
            ->leftJoin('users', 'users.id', '=', 'orders.owner_id')
            ->where('purchased.product_id', $request->input('product_id'))
            ->where('orders.state', '=', 2)
            ->whereNotIn('orders.owner_id',$class_user_id_arr);
        
        if ($request->has('user_name'))
        $result = $result->where('users.name', 'like', '%' . $request->input('user_name') . '%');
        if ($request->has('user_phone'))
        $result = $result->where('users.phone', 'like', '%' . $request->input('user_phone') . '%');
        
        $count = $result->count();
        $result = $result
            ->offset($request->input('offset'))
            ->limit($request->input('length'))
            ->get();
        $result = [
            'data' => $result,
            'total' => $count
        ];
        return $this->success($result);
    }
    
    public function search(Request $request) {

        $validator = Validator::make($request->all(), [
            'offset' => 'required|integer|min:0',
            'length' => 'required|integer|min:1',
            'name' => 'string|max:100',
            'phone' => 'string|max:11',
            'company' => 'string|max:500',
            'idcard' => 'string|max:18',
            'id' => 'integer|min:0',
            'shop_id' => 'integer|min:0',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('users')
            ->select([
                'users.id as id',
                'users.name as name',
                'users.idcard as idcard',
                'users.email as email',
                'users.company as company',
                'users.phone as phone',
                'users.balance as balance',
            ]);
        if ($request->has('name'))
            $result = $result->where('users.name', 'like', '%'.$request->input('name').'%');
        if ($request->has('phone'))
            $result = $result->where('users.phone', 'like', '%'.$request->input('phone').'%');
        if ($request->has('company'))
            $result = $result->where('users.company', 'like', '%'.$request->input('company').'%');
        if ($request->has('idcard'))
            $result = $result->where('users.idcard', 'like', '%'.$request->input('idcard').'%');
        if ($request->has('id'))
            $result = $result->where('users.id', '=', $request->input('id'));
        if ($request->has('shop_id'))
            $result = $result->where('users.shop_id', '=', $request->input('shop_id'));
        $count = $result->count();
        $result = $result
            ->offset($request->input('offset'))
            ->limit($request->input('length'))
            ->get();
        
        $result = [
            'data' => $result,
            'total' => $count
        ];
        return $this->success($result);
    }
    public function isPay(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('users')
            ->select([
                'users.name as user_name',
                'purchased_order_id.product_id as product_id',
                'purchased_order_id.id as purchased_id',
            ]);
        $result = $result->leftJoin('orders as orders_owner_id', 'orders_owner_id.owner_id', '=', 'users.id');
        $result = $result->leftJoin('purchased as purchased_order_id', 'purchased_order_id.order_id', '=', 'orders_owner_id.id');
        if ($request->has('product_id'))
            $result = $result->where('purchased_order_id.product_id', '=', $request->input('product_id'));
        $result = $result->where('orders_owner_id.owner_id', '=', $this->token->id);
        $result = $result->where('orders_owner_id.state', '=', '2');
        $result = $result->get();
        if($result){
            return $this->success(false);
        } 
        return $this->success(true);
    }
    public function balance(Request $request) {

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|min:0',
            'balance' => 'numeric',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('users');
        $info = 'usersController->balance: ';
        if ($request->has('id'))
            $result = $result->where('users.id', '=', $request->input('id'));
        $info = $info . 'with: {'; 
        if ($request->has('id')) 
            $info = $info . 'id => ' . $request->input('id') . ', '; 
        $info = $info . 'id => ' . $request->input('id') . ', ';
        $info = $info . "}, ";
        $data =[];
        $info = $info . 'data: {';
        if ($request->has('balance')){
            $data["balance"] = $request->input('balance');
            $info = $info . 'balance => ' . $request->input('balance') . ', ';
        }
        $data["updated_at"] = date('Y-m-d H:i:s');
        $info = $info . 'updated_at => ' . date('Y-m-d H:i:s') . ', ';
        $info = $info . "}";
        Log::info($info);
        $result->update($data);
        return $this->success();
    }
    public function id_arr_balance(Request $request) {

        $validator = Validator::make($request->all(), [
            'balance' => 'numeric',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('users');
        $info = 'usersController->id_arr_balance: ';
        if ($request->has('id'))
            $result = $result->where('users.id', $request->input('id'));
        $info = $info . 'with: {'; 
        if ($request->has('id')) 
            $info = $info . 'id => ' . $request->input('id') . ', '; 
        if ($request->has('id_arr')){
            $result = $result->whereIn('id', json_decode($request->input('id_arr')));
            $info = $info . "id =>" . $request->input('id_arr');
        }
        $info = $info . "}, ";
        $data =[];
        $info = $info . 'data: {';
        if ($request->has('balance')){
            $data["balance"] = $request->input('balance');
            $info = $info . 'balance => ' . $request->input('balance') . ', ';
        }
        $data["updated_at"] = date('Y-m-d H:i:s');
        $info = $info . 'updated_at => ' . date('Y-m-d H:i:s') . ', ';
        $info = $info . "}";
        Log::info($info);
        $result->update($data);
        return $this->success();
    }
    public function idcard_arr_balance(Request $request) {

        $validator = Validator::make($request->all(), [
            'balance' => 'numeric',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('users');
        $info = 'usersController->idcard_arr_balance: ';
        if ($request->has('id'))
            $result = $result->where('users.id', $request->input('id'));
        $info = $info . 'with: {'; 
        if ($request->has('id')) 
            $info = $info . 'id => ' . $request->input('id') . ', '; 
        if ($request->has('idcard_arr')){
            $result = $result->whereIn('idcard', json_decode($request->input('idcard_arr')));
            $info = $info . "idcard =>" . $request->input('idcard_arr');
        }
        $info = $info . "}, ";
        $data =[];
        $info = $info . 'data: {';
        if ($request->has('balance')){
            $data["balance"] = $request->input('balance');
            $info = $info . 'balance => ' . $request->input('balance') . ', ';
        }
        $data["updated_at"] = date('Y-m-d H:i:s');
        $info = $info . 'updated_at => ' . date('Y-m-d H:i:s') . ', ';
        $info = $info . "}";
        Log::info($info);
        $result->update($data);
        return $this->success();
    }
    public function getTokenByUserId(Request $request) 
    {
        $result = \App\Utils\UserUtil::getTokenByUserId($request);
        return $this->success($result);
    }
    public function balance_token(Request $request) {

        $validator = Validator::make($request->all(), [
            'balance' => 'numeric',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('users');
        $info = 'usersController->balance_token: ';
        $result = $result->where('users.id', '=', $this->token->id);
        $info = $info . 'with: {'; 
        if ($request->has('id')) 
            $info = $info . 'id => ' . $request->input('id') . ', '; 
        $info = $info . 'id => ' . $request->input('token.id') . ', ';
        $info = $info . "}, ";
        $data =[];
        $info = $info . 'data: {';
        if ($request->has('balance')){
            $data["balance"] = $request->input('balance');
            $info = $info . 'balance => ' . $request->input('balance') . ', ';
        }
        $data["updated_at"] = date('Y-m-d H:i:s');
        $info = $info . 'updated_at => ' . date('Y-m-d H:i:s') . ', ';
        $info = $info . "}";
        Log::info($info);
        $result->update($data);
        return $this->success();
    }
    
    public function user_by_idcard(Request $request) {

        $validator = Validator::make($request->all(), [
            'offset' => 'required|integer|min:0',
            'length' => 'required|integer|min:1',
            'idcard' => 'string|max:18',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('users')
            ->select([
                'users.id as id',
                'users.name as name',
            ]);
        if ($request->has('idcard'))
            $result = $result->where('users.idcard', '=', $request->input('idcard'));
        $count = $result->count();
        $result = $result
            ->offset($request->input('offset'))
            ->limit($request->input('length'))
            ->get();
        
        $result = [
            'data' => $result,
            'total' => $count
        ];
        return $this->success($result);
    }
    
    public function idcard_arr_id(Request $request) {

        $validator = Validator::make($request->all(), [
            'offset' => 'required|integer|min:0',
            'length' => 'required|integer|min:1',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('users')
            ->select([
                'users.id as id',
            ]);
        if ($request->has('idcard_arr'))
            $result = $result->whereIn('users.idcard', json_decode($request->input('idcard_arr')));
        $count = $result->count();
        $result = $result
            ->offset($request->input('offset'))
            ->limit($request->input('length'))
            ->get();
        
        $result = [
            'data' => $result,
            'total' => $count
        ];
        return $this->success($result);
    }
    public function register(Request $request) 
    {
        $result = \App\Utils\UserUtil::register($request);
        return $this->success($result);
    }
}
