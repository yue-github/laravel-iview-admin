<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Validator;

class AttributeController extends Controller
{
    
    public function search(Request $request) {

        $validator = Validator::make($request->all(), [
            'offset' => 'required|integer|min:0',
            'length' => 'required|integer|min:1',
            'id' => 'integer|min:0',
            'shop_id' => 'integer|min:0',
            'name' => 'string|max:100',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('attribute')
            ->select([
                'attribute.id as id',
                'attribute.name as name',
            ]);
        if ($request->has('id'))
            $result = $result->where('attribute.id', '=', $request->input('id'));
        if ($request->has('shop_id'))
            $result = $result->where('attribute.shop_id', '=', $request->input('shop_id'));
        if ($request->has('name'))
            $result = $result->where('attribute.name', '=', $request->input('name'));
        $count = $result->count();
        $result = $result
            ->offset($request->input('offset'))
            ->limit($request->input('length'))
            ->get();
        
        for($result_i = 0; $result_i < count($result); $result_i++) {
            $attribute_value_type = DB::table('attribute_value_type')->select([                'attribute_value_attribute_value_id.id as attribute_value_id',
                'attribute_value_attribute_value_id.name as attribute_value_name',
                'attribute_value_attribute_value_id.sort as attribute_value_sort',
            ]);
        $attribute_value_type = $attribute_value_type->leftJoin('attribute_value as attribute_value_attribute_value_id', 'attribute_value_attribute_value_id.id', '=', 'attribute_value_type.attribute_value_id');
        $attribute_value_type = $attribute_value_type->where('attribute_id', $result[$result_i]->id);
        $attribute_value_type = $attribute_value_type->get();
        $result[$result_i]->attribute_value_type = $attribute_value_type;
        }
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
            'shop_id' => 'integer|min:0',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('attribute')
            ->select([
                'attribute.id as id',
                'attribute.name as name',
            ]);
        if ($request->has('shop_id'))
            $result = $result->where('attribute.shop_id', '=', $request->input('shop_id'));
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
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('attribute');
        $info = 'attributeController->shop_edit: ';
        if ($request->has('id'))
            $result = $result->where('attribute.id', '=', $request->input('id'));
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

        $result = DB::table('attribute');
        if ($request->has('id'))
            $result = $result->where('attribute.id', '=', $request->input('id'));
        $result->delete();
        return $this->success();
    }
    public function shop_create(Request $request) {

        $validator = Validator::make($request->all(), [
            'name' => 'string|max:100',
            'shop_id' => 'integer|min:0',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        DB::table('attribute')->insert([
            'name' => $request->input('name'),
            'shop_id' => $request->input('shop_id'),
        ]);
        return $this->success();
    }
    
    public function shop_get_value(Request $request) {

        $validator = Validator::make($request->all(), [
            'offset' => 'required|integer|min:0',
            'length' => 'required|integer|min:1',
            'id' => 'integer|min:0',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('attribute')
            ->select([
                'attribute.id as id',
                'attribute.name as name',
            ]);
        if ($request->has('id'))
            $result = $result->where('attribute.id', '=', $request->input('id'));
        $count = $result->count();
        $result = $result
            ->offset($request->input('offset'))
            ->limit($request->input('length'))
            ->get();
        
        for($result_i = 0; $result_i < count($result); $result_i++) {
            $attribute_value_type = DB::table('attribute_value_type')->select([                'attribute_value_attribute_value_id.id as attribute_value_id',
                'attribute_value_attribute_value_id.name as attribute_value_name',
                'attribute_value_attribute_value_id.sort as attribute_value_sort',
            ]);
        $attribute_value_type = $attribute_value_type->leftJoin('attribute_value as attribute_value_attribute_value_id', 'attribute_value_attribute_value_id.id', '=', 'attribute_value_type.attribute_value_id');
        $attribute_value_type = $attribute_value_type->where('attribute_id', $result[$result_i]->id);
        $attribute_value_type = $attribute_value_type->get();
        $result[$result_i]->attribute_value_type = $attribute_value_type;
        }
        $result = [
            'data' => $result,
            'total' => $count
        ];
        return $this->success($result);
    }
}
