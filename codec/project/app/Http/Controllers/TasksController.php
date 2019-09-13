<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Validator;

class TasksController extends Controller
{
    public function create(Request $request) {

        $validator = Validator::make($request->all(), [
            'shop_id' => 'required|integer|min:0',
            'name' => 'required|string|max:100',
            'desc' => 'string',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        DB::table('tasks')->insert([
            'shop_id' => $request->input('shop_id'),
            'name' => $request->input('name'),
            'desc' => $request->input('desc'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        return $this->success();
    }
    public function edit(Request $request) {

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|min:0',
            'name' => 'required|string|max:100',
            'desc' => 'string',
            'start_date_time' => 'date',
            'end_date_time' => 'date',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('tasks');
        $info = 'tasksController->edit: ';
        if ($request->has('id'))
            $result = $result->where('tasks.id', '=', $request->input('id'));
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
        if ($request->has('desc')){
            $data["desc"] = $request->input('desc');
            $info = $info . 'desc => ' . $request->input('desc') . ', ';
        }
        if ($request->has('start_date_time')){
            $data["start_date_time"] = $request->input('start_date_time');
            $info = $info . 'start_date_time => ' . $request->input('start_date_time') . ', ';
        }
        if ($request->has('end_date_time')){
            $data["end_date_time"] = $request->input('end_date_time');
            $info = $info . 'end_date_time => ' . $request->input('end_date_time') . ', ';
        }
        $data["updated_at"] = date('Y-m-d H:i:s');
        $info = $info . 'updated_at => ' . date('Y-m-d H:i:s') . ', ';
        $info = $info . "}";
        Log::info($info);
        $result->update($data);
        return $this->success();
    }
    
    public function get(Request $request) {

        $validator = Validator::make($request->all(), [
            'id' => 'integer|min:0',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('tasks')
            ->select([
                'tasks.name as name',
                'tasks.desc as desc',
            ]);
        if ($request->has('id'))
            $result = $result->where('tasks.id', '=', $request->input('id'));
        $result = $result->first();
        return $this->success($result);
    }
    public function delete(Request $request) {

        $validator = Validator::make($request->all(), [
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('tasks');
        if ($request->has('id'))
            $result = $result->where('tasks.id', '=', $request->input('id'));
        $result->delete();
        return $this->success();
    }
    
    public function shop_search(Request $request) {

        $validator = Validator::make($request->all(), [
            'offset' => 'required|integer|min:0',
            'length' => 'required|integer|min:1',
            'name' => 'string|max:100',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('tasks')
            ->select([
                'tasks.id as id',
                'tasks.shop_id as shop_id',
                'tasks.name as name',
                'tasks.desc as desc',
            ]);
        if ($request->has('name'))
            $result = $result->where('tasks.name', 'like', '%'.$request->input('name').'%');
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
