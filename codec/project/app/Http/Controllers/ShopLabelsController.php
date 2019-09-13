<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Validator;

class ShopLabelsController extends Controller
{
    
    public function search(Request $request) {

        $validator = Validator::make($request->all(), [
            'offset' => 'required|integer|min:0',
            'length' => 'required|integer|min:1',
            'name' => 'string|max:100',
            'parent_id' => 'integer|min:0',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('shop_labels')
            ->select([
                'shop_labels.id as id',
                'shop_labels.parent_id as parent_id',
                'shop_labels.name as name',
            ]);
        if ($request->has('name'))
            $result = $result->where('shop_labels.name', 'like', '%'.$request->input('name').'%');
        if ($request->has('parent_id'))
            $result = $result->where('shop_labels.parent_id', '=', $request->input('parent_id'));
        $result = $result->where('shop_labels.onsale', '=', '1');
        $result = $result->orderBy('shop_labels.sort', 'desc');
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
    
    public function all(Request $request) {

        $validator = Validator::make($request->all(), [
            'name' => 'string|max:100',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('shop_labels')
            ->select([
                'shop_labels.id as id',
                'shop_labels.parent_id as parent_id',
                'shop_labels.name as name',
            ]);
        if ($request->has('name'))
            $result = $result->where('shop_labels.name', 'like', '%'.$request->input('name').'%');
        $result = $result->where('shop_labels.onsale', '=', '1');
        $result = $result->orderBy('shop_labels.sort', 'desc');
        $result = $result->get();
        return $this->success($result);
    }
    public function edit(Request $request) {

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|min:0',
            'parent_id' => 'required|integer|min:0',
            'name' => 'string|max:100',
            'onsale' => 'integer',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('shop_labels');
        $info = 'shop_labelsController->edit: ';
        if ($request->has('id'))
            $result = $result->where('shop_labels.id', '=', $request->input('id'));
        $info = $info . 'with: {'; 
        if ($request->has('id')) 
            $info = $info . 'id => ' . $request->input('id') . ', '; 
        $info = $info . 'id => ' . $request->input('id') . ', ';
        $info = $info . "}, ";
        $data =[];
        $info = $info . 'data: {';
        if ($request->has('parent_id')){
            $data["parent_id"] = $request->input('parent_id');
            $info = $info . 'parent_id => ' . $request->input('parent_id') . ', ';
        }
        if ($request->has('name')){
            $data["name"] = $request->input('name');
            $info = $info . 'name => ' . $request->input('name') . ', ';
        }
        if ($request->has('onsale')){
            $data["onsale"] = $request->input('onsale');
            $info = $info . 'onsale => ' . $request->input('onsale') . ', ';
        }
        $info = $info . "}";
        Log::info($info);
        $result->update($data);
        return $this->success();
    }
    public function create(Request $request) {

        $validator = Validator::make($request->all(), [
            'parent_id' => 'required|integer|min:0',
            'name' => 'string|max:100',
            'onsale' => 'integer',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        DB::table('shop_labels')->insert([
            'parent_id' => $request->input('parent_id'),
            'name' => $request->input('name'),
            'onsale' => $request->input('onsale'),
        ]);
        return $this->success();
    }
    public function delete(Request $request) {

        $validator = Validator::make($request->all(), [
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('shop_labels');
        if ($request->has('id'))
            $result = $result->where('shop_labels.id', '=', $request->input('id'));
        $result->delete();
        return $this->success();
    }
}
