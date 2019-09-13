<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Validator;

class InstructorController extends Controller
{
    public function login(Request $request) {
    
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string|max:11',
            'password' => 'required|string',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $user = DB::table("instructor")
            ->where("phone", "=", $request->input('phone'))
            ->where("password", "=", md5($request->input('password')))
            ->first();
        if (!$user) {
            return $this->fails('用户名或密码错误');
        }

        $data = ['id'=>$user->id];


        $token = Crypt::encrypt(json_encode($data).'.'.time());

        $data['token'] = $token;

        return $this->success($data);
    }
    public function create(Request $request) {

        $validator = Validator::make($request->all(), [
            'phone' => 'required|string|max:11',
            'name' => 'required|string|max:100',
            'idcard' => 'string|max:18',
            'company' => 'string|max:100',
            'email' => 'string|email',
            'desc' => 'string|max:100',
            'img' => 'string|max:100',
            'enabled' => 'boolean',
            'password' => 'required|string',
            'shop_id' => 'integer|min:0',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $phone = $request->input('phone');
        if (DB::table('instructor')->where('phone', "=", $phone)->count() > 0) {
            return $this->fails('���û���ע��');
        }
        
        DB::table('instructor')->insert([
            'phone' => $request->input('phone'),
            'name' => $request->input('name'),
            'idcard' => $request->input('idcard'),
            'company' => $request->input('company'),
            'email' => $request->input('email'),
            'desc' => $request->input('desc'),
            'img' => $request->input('img'),
            'enabled' => $request->input('enabled'),
            'password' => md5($request->input('password')),
            'shop_id' => $request->input('shop_id'),
        ]);
        return $this->success();
    }
    public function edit(Request $request) {

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|min:0',
            'name' => 'required|string|max:100',
            'idcard' => 'string|max:18',
            'company' => 'string|max:100',
            'phone' => 'required|string|max:11',
            'email' => 'string|email',
            'desc' => 'string|max:100',
            'img' => 'string|max:100',
            'enabled' => 'boolean',
            'password' => 'required|string',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('instructor');
        $info = 'instructorController->edit: ';
        if ($request->has('id'))
            $result = $result->where('instructor.id', '=', $request->input('id'));
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
        if ($request->has('idcard')){
            $data["idcard"] = $request->input('idcard');
            $info = $info . 'idcard => ' . $request->input('idcard') . ', ';
        }
        if ($request->has('company')){
            $data["company"] = $request->input('company');
            $info = $info . 'company => ' . $request->input('company') . ', ';
        }
        if ($request->has('phone')){
            $data["phone"] = $request->input('phone');
            $info = $info . 'phone => ' . $request->input('phone') . ', ';
        }
        if ($request->has('email')){
            $data["email"] = $request->input('email');
            $info = $info . 'email => ' . $request->input('email') . ', ';
        }
        if ($request->has('desc')){
            $data["desc"] = $request->input('desc');
            $info = $info . 'desc => ' . $request->input('desc') . ', ';
        }
        if ($request->has('img')){
            $data["img"] = $request->input('img');
            $info = $info . 'img => ' . $request->input('img') . ', ';
        }
        if ($request->has('enabled')){
            $data["enabled"] = $request->input('enabled');
            $info = $info . 'enabled => ' . $request->input('enabled') . ', ';
        }
        if ($request->has('password')){
            $data["password"] = $request->input('password');
            $info = $info . 'password => ' . $request->input('password') . ', ';
        }
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
        $user = DB::table("instructor")
            ->where("id", $this->token->id)
            ->where("password", md5($opwd))->first();

        if (!$user) {
            return $this->fails('旧密码不正确');
        }

        DB::table("instructor")->where("id", $this->token->id)->update([
            'password'=>md5($request->input('password')),
        ]);

        return $this->success();
    }
    
    public function search(Request $request) {

        $validator = Validator::make($request->all(), [
            'offset' => 'required|integer|min:0',
            'length' => 'required|integer|min:1',
            'name' => 'string|max:100',
            'phone' => 'string|max:11',
            'shop_id' => 'integer|min:0',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('instructor')
            ->select([
                'instructor.id as id',
                'instructor.name as name',
                'instructor.idcard as idcard',
                'instructor.company as company',
                'instructor.phone as phone',
                'instructor.email as email',
                'instructor.desc as desc',
                'instructor.img as img',
                'instructor.enabled as enabled',
                'instructor.password as password',
            ]);
        if ($request->has('name'))
            $result = $result->where('instructor.name', 'like', '%'.$request->input('name').'%');
        if ($request->has('phone'))
            $result = $result->where('instructor.phone', 'like', '%'.$request->input('phone').'%');
        if ($request->has('shop_id'))
            $result = $result->where('instructor.shop_id', '=', $request->input('shop_id'));
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
    
    public function get(Request $request) {

        $validator = Validator::make($request->all(), [
            'id' => 'integer|min:0',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('instructor')
            ->select([
                'instructor.id as id',
                'instructor.name as name',
                'instructor.idcard as idcard',
                'instructor.company as company',
                'instructor.phone as phone',
                'instructor.email as email',
                'instructor.desc as desc',
                'instructor.img as img',
                'instructor.enabled as enabled',
                'instructor.password as password',
            ]);
        if ($request->has('id'))
            $result = $result->where('instructor.id', '=', $request->input('id'));
        $result = $result->first();
        return $this->success($result);
    }
    
    public function profile(Request $request) {

        $validator = Validator::make($request->all(), [
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('instructor')
            ->select([
                'instructor.id as id',
                'instructor.name as name',
                'instructor.idcard as idcard',
                'instructor.company as company',
                'instructor.phone as phone',
                'instructor.email as email',
                'instructor.desc as desc',
                'instructor.img as img',
                'instructor.password as password',
            ]);
        $result = $result->where('instructor.id', '=', $this->token->id);
        $result = $result->first();
        return $this->success($result);
    }
    public function delete(Request $request) {

        $validator = Validator::make($request->all(), [
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('instructor');
        if ($request->has('id'))
            $result = $result->where('instructor.id', '=', $request->input('id'));
        $result->delete();
        return $this->success();
    }
}
