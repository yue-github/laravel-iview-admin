<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Validator;

class BannerController extends Controller
{
    
    public function search(Request $request) {

        $validator = Validator::make($request->all(), [
            'offset' => 'required|integer|min:0',
            'length' => 'required|integer|min:1',
            'shop_id' => 'integer|min:0',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('banner')
            ->select([
                'banner.name as name',
                'banner.file_name as file_name',
                'banner.shop_id as shop_id',
                'banner.sort as sort',
                'banner.url as url',
                'banner.id as id',
                'banner.color as color',
            ]);
        if ($request->has('shop_id'))
            $result = $result->where('banner.shop_id', '=', $request->input('shop_id'));
        $result = $result->orderBy('banner.sort', 'asc');
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
    
    public function shop_search(Request $request) {

        $validator = Validator::make($request->all(), [
            'offset' => 'required|integer|min:0',
            'length' => 'required|integer|min:1',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('banner')
            ->select([
                'banner.name as name',
                'banner.file_name as file_name',
                'banner.shop_id as shop_id',
                'banner.sort as sort',
                'banner.url as url',
                'banner.id as id',
                'banner.color as color',
            ]);
        $result = $result->where('banner.shop_id', '=', $this->token->shop_id);
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
    
    public function search_not_id(Request $request) {

        $validator = Validator::make($request->all(), [
            'offset' => 'required|integer|min:0',
            'length' => 'required|integer|min:1',
            'shop_id' => 'integer|min:0',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('banner')
            ->select([
                'banner.id as id',
                'banner.sort as sort',
            ]);
        if ($request->has('shop_id'))
            $result = $result->where('banner.shop_id', '=', $request->input('shop_id'));
        if ($request->has('id_arr'))
            $result = $result->whereNotIn('banner.id', json_decode($request->input('id_arr')));
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
            'name' => 'string|max:100',
            'file_name' => 'string|max:100',
            'shop_id' => 'integer|min:0',
            'sort' => 'integer|min:0',
            'url' => 'string',
            'color' => 'string|max:100',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        DB::table('banner')->insert([
            'name' => $request->input('name'),
            'file_name' => $request->input('file_name'),
            'shop_id' => $request->input('shop_id'),
            'sort' => $request->input('sort'),
            'url' => $request->input('url'),
            'color' => $request->input('color'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        return $this->success();
    }
    public function update(Request $request) {

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|min:0',
            'name' => 'string|max:100',
            'file_name' => 'string|max:100',
            'shop_id' => 'integer|min:0',
            'sort' => 'integer|min:0',
            'url' => 'string',
            'color' => 'string|max:100',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('banner');
        $info = 'bannerController->update: ';
        if ($request->has('id'))
            $result = $result->where('banner.id', '=', $request->input('id'));
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
        if ($request->has('file_name')){
            $data["file_name"] = $request->input('file_name');
            $info = $info . 'file_name => ' . $request->input('file_name') . ', ';
        }
        if ($request->has('shop_id')){
            $data["shop_id"] = $request->input('shop_id');
            $info = $info . 'shop_id => ' . $request->input('shop_id') . ', ';
        }
        if ($request->has('sort')){
            $data["sort"] = $request->input('sort');
            $info = $info . 'sort => ' . $request->input('sort') . ', ';
        }
        if ($request->has('url')){
            $data["url"] = $request->input('url');
            $info = $info . 'url => ' . $request->input('url') . ', ';
        }
        if ($request->has('color')){
            $data["color"] = $request->input('color');
            $info = $info . 'color => ' . $request->input('color') . ', ';
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

        $result = DB::table('banner');
        if ($request->has('id'))
            $result = $result->where('banner.id', '=', $request->input('id'));
        $result->delete();
        return $this->success();
    }
}
