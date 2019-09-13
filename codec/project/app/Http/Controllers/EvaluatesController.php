<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Validator;

class EvaluatesController extends Controller
{
    public function create(Request $request) {

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer|min:0',
            'grade' => 'required|integer',
            'class_id' => 'required|integer|min:0',
            'content' => 'required|string',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        DB::table('evaluates')->insert([
            'user_id' => $request->input('user_id'),
            'grade' => $request->input('grade'),
            'class_id' => $request->input('class_id'),
            'content' => $request->input('content'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        return $this->success();
    }
    public function edit(Request $request) {

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer|min:0',
            'class_id' => 'required|integer|min:0',
            'grade' => 'required|integer',
            'content' => 'required|string',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('evaluates');
        $info = 'evaluatesController->edit: ';
        if ($request->has('user_id'))
            $result = $result->where('evaluates.user_id', '=', $request->input('user_id'));
        if ($request->has('class_id'))
            $result = $result->where('evaluates.class_id', '=', $request->input('class_id'));
        $info = $info . 'with: {'; 
        if ($request->has('id')) 
            $info = $info . 'id => ' . $request->input('id') . ', '; 
        $info = $info . 'user_id => ' . $request->input('user_id') . ', ';
        $info = $info . 'class_id => ' . $request->input('class_id') . ', ';
        $info = $info . "}, ";
        $data =[];
        $info = $info . 'data: {';
        if ($request->has('grade')){
            $data["grade"] = $request->input('grade');
            $info = $info . 'grade => ' . $request->input('grade') . ', ';
        }
        if ($request->has('content')){
            $data["content"] = $request->input('content');
            $info = $info . 'content => ' . $request->input('content') . ', ';
        }
        $data["updated_at"] = date('Y-m-d H:i:s');
        $info = $info . 'updated_at => ' . date('Y-m-d H:i:s') . ', ';
        $info = $info . "}";
        Log::info($info);
        $result->update($data);
        return $this->success();
    }
    public function delete(Request $request) {

        $validator = Validator::make($request->all(), [
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('evaluates');
        if ($request->has('id'))
            $result = $result->where('evaluates.id', '=', $request->input('id'));
        $result->delete();
        return $this->success();
    }
    
    public function search(Request $request) {

        $validator = Validator::make($request->all(), [
            'offset' => 'required|integer|min:0',
            'length' => 'required|integer|min:1',
            'class_id' => 'integer|min:0',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('evaluates')
            ->select([
                'users_user_id.id as id',
                'users_user_id.name as name',
                'users_user_id.company as company',
                'users_user_id.phone as phone',
                'users_user_id.email as email',
                'users_user_id.balance as balance',
                'evaluates.grade as grade',
                'class_class_id.name as class_name',
                'evaluates.content as content',
            ]);
        $result = $result->leftJoin('users as users_user_id', 'users_user_id.id', '=', 'evaluates.user_id');
        $result = $result->leftJoin('class as class_class_id', 'class_class_id.id', '=', 'evaluates.class_id');
        if ($request->has('class_id'))
            $result = $result->where('evaluates.class_id', '=', $request->input('class_id'));
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
}
