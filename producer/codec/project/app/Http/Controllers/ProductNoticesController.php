<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Validator;

class ProductNoticesController extends Controller
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

        $result = DB::table('product_notices')
            ->select([
                'product_notices.id as id',
                'product_notices.title as title',
                'product_notices.url as url',
                'product_notices.content as content',
            ]);
        if ($request->has('package_id'))
            $result = $result->where('product_notices.package_id', '=', $request->input('package_id'));
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
            'title' => 'string|max:100',
            'content' => 'string',
            'url' => 'string',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        DB::table('product_notices')->insert([
            'package_id' => $request->input('package_id'),
            'title' => $request->input('title'),
            'content' => $request->input('content'),
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

        $result = DB::table('product_notices');
        if ($request->has('id'))
            $result = $result->where('product_notices.id', '=', $request->input('id'));
        $result->delete();
        return $this->success();
    }
    public function update(Request $request) {

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|min:0',
            'title' => 'string|max:100',
            'content' => 'string',
            'url' => 'string',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('product_notices');
        $info = 'product_noticesController->update: ';
        if ($request->has('id'))
            $result = $result->where('product_notices.id', '=', $request->input('id'));
        $info = $info . 'with: {'; 
        if ($request->has('id')) 
            $info = $info . 'id => ' . $request->input('id') . ', '; 
        $info = $info . 'id => ' . $request->input('id') . ', ';
        $info = $info . "}, ";
        $data =[];
        $info = $info . 'data: {';
        if ($request->has('title')){
            $data["title"] = $request->input('title');
            $info = $info . 'title => ' . $request->input('title') . ', ';
        }
        if ($request->has('content')){
            $data["content"] = $request->input('content');
            $info = $info . 'content => ' . $request->input('content') . ', ';
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
    
    public function notice(Request $request) {

        $validator = Validator::make($request->all(), [
            'id' => 'integer|min:0',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('product_notices')
            ->select([
                'product_notices.title as title',
                'product_notices.content as content',
                'product_notices.url as url',
            ]);
        if ($request->has('id'))
            $result = $result->where('product_notices.id', '=', $request->input('id'));
        $result = $result->first();
        return $this->success($result);
    }
}
