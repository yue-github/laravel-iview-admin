<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Validator;

class ProductSmallBannerController extends Controller
{
    
    public function search(Request $request) {

        $validator = Validator::make($request->all(), [
            'offset' => 'required|integer|min:0',
            'length' => 'required|integer|min:1',
            'package_id' => 'integer|min:0',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('product_small_banner')
            ->select([
                'product_small_banner.id as id',
                'product_small_banner.img_file_name as img_file_name',
                'product_small_banner.url as url',
            ]);
        if ($request->has('package_id'))
            $result = $result->where('product_small_banner.package_id', '=', $request->input('package_id'));
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
    public function insert(Request $request) {

        $validator = Validator::make($request->all(), [
            'package_id' => 'integer|min:0',
            'img_file_name' => 'string|max:100',
            'url' => 'string',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        DB::table('product_small_banner')->insert([
            'package_id' => $request->input('package_id'),
            'img_file_name' => $request->input('img_file_name'),
            'url' => $request->input('url'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        return $this->success();
    }
    public function delete(Request $request) {

        $validator = Validator::make($request->all(), [
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('product_small_banner');
        if ($request->has('id'))
            $result = $result->where('product_small_banner.id', '=', $request->input('id'));
        $result->delete();
        return $this->success();
    }
    
    public function banner(Request $request) {

        $validator = Validator::make($request->all(), [
            'id' => 'integer|min:0',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('product_small_banner')
            ->select([
                'product_small_banner.id as id',
                'product_small_banner.img_file_name as img_file_name',
                'product_small_banner.url as url',
                'product_small_banner.package_id as package_id',
            ]);
        if ($request->has('id'))
            $result = $result->where('product_small_banner.id', '=', $request->input('id'));
        $result = $result->first();
        return $this->success($result);
    }
    public function update(Request $request) {

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|min:0',
            'img_file_name' => 'string|max:100',
            'url' => 'string',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('product_small_banner');
        $info = 'product_small_bannerController->update: ';
        if ($request->has('id'))
            $result = $result->where('product_small_banner.id', '=', $request->input('id'));
        $info = $info . 'with: {'; 
        if ($request->has('id')) 
            $info = $info . 'id => ' . $request->input('id') . ', '; 
        $info = $info . 'id => ' . $request->input('id') . ', ';
        $info = $info . "}, ";
        $data =[];
        $info = $info . 'data: {';
        if ($request->has('img_file_name')){
            $data["img_file_name"] = $request->input('img_file_name');
            $info = $info . 'img_file_name => ' . $request->input('img_file_name') . ', ';
        }
        if ($request->has('url')){
            $data["url"] = $request->input('url');
            $info = $info . 'url => ' . $request->input('url') . ', ';
        }
        $data["updated_at"] = date('Y-m-d H:i:s');
        $info = $info . 'updated_at => ' . date('Y-m-d H:i:s') . ', ';
        $info = $info . "}";
        Log::info($info);
        $result->update($data);
        return $this->success();
    }
}
