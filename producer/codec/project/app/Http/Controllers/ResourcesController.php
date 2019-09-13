<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Validator;

class ResourcesController extends Controller
{
    public function batch_insert(Request $request) {
        $dataArr = json_decode($request->input('data_arr'));
        var_dump($dataArr);
        $insertData = array();

        foreach ($dataArr as $item) {
            $data = array();
            if (property_exists($item, 'name'))
            $data['name'] = $item->name;
            if (property_exists($item, 'resources'))
            $data['resources'] = $item->resources;
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['updated_at'] = date('Y-m-d H:i:s');
            array_push($insertData, $data);
        }

        DB::table('resources')->insert($insertData);
        return $this->success();
    }
    public function create_discuss(Request $request) {
        DB::beginTransaction();
        $resource_id = DB::table("resources")->insertGetId([
            "shop_id" => $this->token->shop_id,
            "name" => $request->input("name"),
            "data" => $request->input("data"),
            "type" => $request->input("type"),
            "created_at" => date("Y-m-d H:i:s"),
            "updated_at" => date("Y-m-d H:i:s"),
          ]);
        $discuss_theme_id = DB::table("discuss_theme")->insertGetId([
            "title" => $request->input("title"),
            "content" => $request->input("content"),
            "sort" => $request->input("sort"),
            "resource_id" => $resource_id,
            "created_at" => date("Y-m-d H:i:s"),
            "updated_at" => date("Y-m-d H:i:s"),
          ]);
        
        DB::commit();
        return $this->success();
    }
    public function excel_topic(Request $request) 
    {
        $result = \App\Utils\FileUtil::import_exercises($request);
        return $this->success($result);
    }
    
    public function get(Request $request) {

        $validator = Validator::make($request->all(), [
            'id' => 'integer|min:0',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('resources')
            ->select([
                'resources.id as id',
                'resources.shop_id as shop_id',
                'resources.name as name',
                'resources.data as data',
                'resources.type as type',
            ]);
        if ($request->has('id'))
            $result = $result->where('resources.id', '=', $request->input('id'));
        $result = $result->first();
        return $this->success($result);
    }
    
    public function shop_search(Request $request) {

        $validator = Validator::make($request->all(), [
            'offset' => 'required|integer|min:0',
            'length' => 'required|integer|min:1',
            'name' => 'string|max:100',
            'id' => 'integer|min:0',
            'type' => 'integer',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('resources')
            ->select([
                'resources.id as id',
                'resources.shop_id as shop_id',
                'resources.name as name',
                'resources.data as data',
                'resources.type as type',
            ]);
        $result = $result->where('resources.shop_id', '=', $this->token->shop_id);
        if ($request->has('name'))
            $result = $result->where('resources.name', 'like', '%'.$request->input('name').'%');
        if ($request->has('id'))
            $result = $result->where('resources.id', '=', $request->input('id'));
        if ($request->has('type'))
            $result = $result->where('resources.type', '=', $request->input('type'));
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
    
    public function shop_search_not_data(Request $request) {

        $validator = Validator::make($request->all(), [
            'offset' => 'required|integer|min:0',
            'length' => 'required|integer|min:1',
            'name' => 'string|max:100',
            'id' => 'integer|min:0',
            'type' => 'integer',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('resources')
            ->select([
                'resources.id as id',
                'resources.shop_id as shop_id',
                'resources.name as name',
                'resources.type as type',
            ]);
        $result = $result->where('resources.shop_id', '=', $this->token->shop_id);
        if ($request->has('name'))
            $result = $result->where('resources.name', 'like', '%'.$request->input('name').'%');
        if ($request->has('id'))
            $result = $result->where('resources.id', '=', $request->input('id'));
        if ($request->has('type'))
            $result = $result->where('resources.type', '=', $request->input('type'));
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
    public function shop_create(Request $request) {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'data' => 'required|string',
            'type' => 'required|integer',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        DB::table('resources')->insert([
            'shop_id' => $this->token->shop_id,
            'name' => $request->input('name'),
            'data' => $request->input('data'),
            'type' => $request->input('type'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        return $this->success();
    }
    public function shop_edit(Request $request) {

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|min:0',
            'name' => 'required|string|max:100',
            'data' => 'required|string',
            'type' => 'required|integer',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('resources');
        $info = 'resourcesController->shop_edit: ';
        if ($request->has('id'))
            $result = $result->where('resources.id', '=', $request->input('id'));
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
        if ($request->has('data')){
            $data["data"] = $request->input('data');
            $info = $info . 'data => ' . $request->input('data') . ', ';
        }
        if ($request->has('type')){
            $data["type"] = $request->input('type');
            $info = $info . 'type => ' . $request->input('type') . ', ';
        }
        $data["updated_at"] = date('Y-m-d H:i:s');
        $info = $info . 'updated_at => ' . date('Y-m-d H:i:s') . ', ';
        $info = $info . "}";
        Log::info($info);
        $result->update($data);
        return $this->success();
    }
    
    public function shop_get(Request $request) {

        $validator = Validator::make($request->all(), [
            'id' => 'integer|min:0',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('resources')
            ->select([
                'resources.shop_id as shop_id',
                'resources.name as name',
                'resources.data as data',
                'resources.type as type',
            ]);
        if ($request->has('id'))
            $result = $result->where('resources.id', '=', $request->input('id'));
        $result = $result->first();
        return $this->success($result);
    }
    public function delete(Request $request) {

        $validator = Validator::make($request->all(), [
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('resources');
        if ($request->has('id'))
            $result = $result->where('resources.id', '=', $request->input('id'));
        $result->delete();
        return $this->success();
    }
}
