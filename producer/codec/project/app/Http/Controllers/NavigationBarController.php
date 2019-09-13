<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Validator;

class NavigationBarController extends Controller
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

        $result = DB::table('navigation_bar')
            ->select([
                'navigation_bar.id as id',
                'navigation_bar.name as name',
                'navigation_bar.url as url',
            ]);
        if ($request->has('shop_id'))
            $result = $result->where('navigation_bar.shop_id', '=', $request->input('shop_id'));
        $result = $result->orderBy('navigation_bar.sort', 'asc');
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

        $result = DB::table('navigation_bar')
            ->select([
                'navigation_bar.id as id',
                'navigation_bar.name as name',
                'navigation_bar.url as url',
                'navigation_bar.sort as sort',
            ]);
        $result = $result->where('navigation_bar.shop_id', '=', $this->token->shop_id);
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
            'name' => 'string|max:100',
            'url' => 'string|max:200',
            'sort' => 'integer',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        DB::table('navigation_bar')->insert([
            'name' => $request->input('name'),
            'url' => $request->input('url'),
            'sort' => $request->input('sort'),
            'shop_id' => $this->token->shop_id,
        ]);
        return $this->success();
    }
    public function shop_edit(Request $request) {

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|min:0',
            'name' => 'string|max:100',
            'url' => 'string|max:200',
            'sort' => 'integer',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('navigation_bar');
        $info = 'navigation_barController->shop_edit: ';
        if ($request->has('id'))
            $result = $result->where('navigation_bar.id', '=', $request->input('id'));
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
        if ($request->has('url')){
            $data["url"] = $request->input('url');
            $info = $info . 'url => ' . $request->input('url') . ', ';
        }
        if ($request->has('sort')){
            $data["sort"] = $request->input('sort');
            $info = $info . 'sort => ' . $request->input('sort') . ', ';
        }
        $info = $info . "}";
        Log::info($info);
        $result->update($data);
        return $this->success();
    }
    public function shop_delete(Request $request) {

        $validator = Validator::make($request->all(), [
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('navigation_bar');
        if ($request->has('id'))
            $result = $result->where('navigation_bar.id', '=', $request->input('id'));
        $result->delete();
        return $this->success();
    }
}
