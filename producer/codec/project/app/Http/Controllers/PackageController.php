<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Validator;

class PackageController extends Controller
{
    
    public function search(Request $request) {

        $validator = Validator::make($request->all(), [
            'offset' => 'required|integer|min:0',
            'length' => 'required|integer|min:1',
            'id' => 'integer|min:0',
            'name' => 'string|max:100',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('package')
            ->select([
                'package.id as id',
                'package.name as name',
                'package.title1 as title1',
                'package.title2 as title2',
                'package.img as img',
                'package.background as background',
                'projects_project_id.start_stydy_time as start_stydy_time',
                'projects_project_id.end_study_time as end_study_time',
            ]);
        $result = $result->leftJoin('package_product as package_product_package_id', 'package_product_package_id.package_id', '=', 'package.id');
        $result = $result->leftJoin('products as products_product_id', 'products_product_id.id', '=', 'package_product_package_id.product_id');
        $result = $result->leftJoin('projects as projects_project_id', 'projects_project_id.id', '=', 'products_product_id.project_id');
        if ($request->has('id'))
            $result = $result->where('package.id', '=', $request->input('id'));
        if ($request->has('name'))
            $result = $result->where('package.name', 'like', '%'.$request->input('name').'%');
        $result = $result->groupBy('package.id');
        $count = count($result->get());
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
    public function search_package(Request $request) 
    {
        $result = \App\Utils\PackageService::search_package($request);
        return $this->success($result);
    }
    
    public function shop_search(Request $request) {

        $validator = Validator::make($request->all(), [
            'offset' => 'required|integer|min:0',
            'length' => 'required|integer|min:1',
            'name' => 'string|max:100',
            'shop_id' => 'integer|min:0',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('package')
            ->select([
                'package.id as id',
                'package.name as name',
                'package.title1 as title1',
                'package.title2 as title2',
                'package.background as background',
                'package.img as img',
            ]);
        if ($request->has('name'))
            $result = $result->where('package.name', 'like', '%'.$request->input('name').'%');
        if ($request->has('shop_id'))
            $result = $result->where('package.shop_id', '=', $request->input('shop_id'));
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
    public function shop_edit(Request $request) {

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|min:0',
            'name' => 'string|max:100',
            'title1' => 'string|max:100',
            'title2' => 'string|max:100',
            'background' => 'string|max:100',
            'img' => 'string|max:100',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('package');
        $info = 'packageController->shop_edit: ';
        if ($request->has('id'))
            $result = $result->where('package.id', '=', $request->input('id'));
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
        if ($request->has('title1')){
            $data["title1"] = $request->input('title1');
            $info = $info . 'title1 => ' . $request->input('title1') . ', ';
        }
        if ($request->has('title2')){
            $data["title2"] = $request->input('title2');
            $info = $info . 'title2 => ' . $request->input('title2') . ', ';
        }
        if ($request->has('background')){
            $data["background"] = $request->input('background');
            $info = $info . 'background => ' . $request->input('background') . ', ';
        }
        if ($request->has('img')){
            $data["img"] = $request->input('img');
            $info = $info . 'img => ' . $request->input('img') . ', ';
        }
        $data["updated_at"] = date('Y-m-d H:i:s');
        $info = $info . 'updated_at => ' . date('Y-m-d H:i:s') . ', ';
        $info = $info . "}";
        Log::info($info);
        $result->update($data);
        return $this->success();
    }
    public function shop_create(Request $request) {

        $validator = Validator::make($request->all(), [
            'name' => 'string|max:100',
            'title1' => 'string|max:100',
            'title2' => 'string|max:100',
            'background' => 'string|max:100',
            'img' => 'string|max:100',
            'shop_id' => 'integer|min:0',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        DB::table('package')->insert([
            'name' => $request->input('name'),
            'title1' => $request->input('title1'),
            'title2' => $request->input('title2'),
            'background' => $request->input('background'),
            'img' => $request->input('img'),
            'shop_id' => $request->input('shop_id'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        return $this->success();
    }
    public function shop_delete(Request $request) {

        $validator = Validator::make($request->all(), [
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('package');
        if ($request->has('id'))
            $result = $result->where('package.id', '=', $request->input('id'));
        $result->delete();
        return $this->success();
    }
}
