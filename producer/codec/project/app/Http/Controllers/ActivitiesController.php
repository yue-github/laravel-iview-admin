<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Validator;

class ActivitiesController extends Controller
{
    
    public function get(Request $request) {

        $validator = Validator::make($request->all(), [
            'id' => 'integer|min:0',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('activities')
            ->select([
                'activities.shop_id as shop_id',
                'activities.name as name',
                'activities.desc as desc',
                'activities.resources as resources',
            ]);
        if ($request->has('id'))
            $result = $result->where('activities.id', '=', $request->input('id'));
        $result = $result->first();
        return $this->success($result);
    }
    
    public function search(Request $request) {

        $validator = Validator::make($request->all(), [
            'offset' => 'required|integer|min:0',
            'length' => 'required|integer|min:1',
            'name' => 'string',
            'shop_id' => 'integer|min:0',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('activities')
            ->select([
                'activities.id as id',
                'activities.shop_id as shop_id',
                'activities.name as name',
                'activities.desc as desc',
                'activities.resources as resources',
                'icon_icon_type_id.file_name as file_name',
                'icon_icon_type_id.type as type',
            ]);
        $result = $result->leftJoin('icon_type as icon_type_icon_type_id', 'icon_type_icon_type_id.id', '=', 'activities.icon_type_id');
        $result = $result->leftJoin('icon as icon_icon_type_id', 'icon_icon_type_id.icon_type_id', '=', 'icon_type_icon_type_id.id');
        if ($request->has('name'))
            $result = $result->where('activities.name', 'like', '%'.$request->input('name').'%');
        if ($request->has('icon_state'))
            $result = $result->where('icon_icon_type_id.state', '=', $request->input('icon_state'));
        if ($request->has('shop_id'))
            $result = $result->where('activities.shop_id', '=', $request->input('shop_id'));
        $result = $result->orderBy('activities.sort', 'desc');
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
    public function create(Request $request) {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'desc' => 'string',
            'resources' => 'json',
            'sort' => 'integer',
            'icon_type_id' => 'integer|min:0',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        DB::table('activities')->insert([
            'shop_id' => $this->token->shop_id,
            'name' => $request->input('name'),
            'desc' => $request->input('desc'),
            'resources' => $request->input('resources'),
            'sort' => $request->input('sort'),
            'icon_type_id' => $request->input('icon_type_id'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        return $this->success();
    }
    public function edit(Request $request) {

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|min:0',
            'shop_id' => 'required|integer|min:0',
            'name' => 'required|string',
            'desc' => 'string',
            'resources' => 'json',
            'sort' => 'integer',
            'icon_type_id' => 'integer|min:0',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('activities');
        $info = 'activitiesController->edit: ';
        if ($request->has('id'))
            $result = $result->where('activities.id', '=', $request->input('id'));
        $info = $info . 'with: {'; 
        if ($request->has('id')) 
            $info = $info . 'id => ' . $request->input('id') . ', '; 
        $info = $info . 'id => ' . $request->input('id') . ', ';
        $info = $info . "}, ";
        $data =[];
        $info = $info . 'data: {';
        if ($request->has('shop_id')){
            $data["shop_id"] = $request->input('shop_id');
            $info = $info . 'shop_id => ' . $request->input('shop_id') . ', ';
        }
        if ($request->has('name')){
            $data["name"] = $request->input('name');
            $info = $info . 'name => ' . $request->input('name') . ', ';
        }
        if ($request->has('desc')){
            $data["desc"] = $request->input('desc');
            $info = $info . 'desc => ' . $request->input('desc') . ', ';
        }
        if ($request->has('resources')){
            $data["resources"] = $request->input('resources');
            $info = $info . 'resources => ' . $request->input('resources') . ', ';
        }
        if ($request->has('sort')){
            $data["sort"] = $request->input('sort');
            $info = $info . 'sort => ' . $request->input('sort') . ', ';
        }
        if ($request->has('icon_type_id')){
            $data["icon_type_id"] = $request->input('icon_type_id');
            $info = $info . 'icon_type_id => ' . $request->input('icon_type_id') . ', ';
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

        $result = DB::table('activities');
        if ($request->has('id'))
            $result = $result->where('activities.id', '=', $request->input('id'));
        $result->delete();
        return $this->success();
    }
    public function shop_edit(Request $request) {

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|min:0',
            'resources' => 'json',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('activities');
        $info = 'activitiesController->shop_edit: ';
        if ($request->has('id'))
            $result = $result->where('activities.id', '=', $request->input('id'));
        $info = $info . 'with: {'; 
        if ($request->has('id')) 
            $info = $info . 'id => ' . $request->input('id') . ', '; 
        $info = $info . 'id => ' . $request->input('id') . ', ';
        $info = $info . "}, ";
        $data =[];
        $info = $info . 'data: {';
        if ($request->has('resources')){
            $data["resources"] = $request->input('resources');
            $info = $info . 'resources => ' . $request->input('resources') . ', ';
        }
        $data["updated_at"] = date('Y-m-d H:i:s');
        $info = $info . 'updated_at => ' . date('Y-m-d H:i:s') . ', ';
        $info = $info . "}";
        Log::info($info);
        $result->update($data);
        return $this->success();
    }
}
