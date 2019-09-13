<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Validator;

class AttributeValueController extends Controller
{
    public function shop_create_value(Request $request) {
        DB::beginTransaction();
        $attribute_value_id = DB::table("attribute_value")->insertGetId([
            "name" => $request->input("attribute_value_name"),
            "sort" => $request->input("attribute_value_sort"),
            "shop_id" => $request->input("shop_id"),
          ]);
        $attribute_value_type_id = DB::table("attribute_value_type")->insertGetId([
            "attribute_id" => $request->input("attribute_id"),
            "attribute_value_id" => $attribute_value_id,
          ]);
        
        DB::commit();
        return $this->success();
    }
    public function shop_edit(Request $request) {

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|min:0',
            'sort' => 'integer',
            'name' => 'string|max:100',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('attribute_value');
        $info = 'attribute_valueController->shop_edit: ';
        if ($request->has('id'))
            $result = $result->where('attribute_value.id', '=', $request->input('id'));
        $info = $info . 'with: {'; 
        if ($request->has('id')) 
            $info = $info . 'id => ' . $request->input('id') . ', '; 
        $info = $info . 'id => ' . $request->input('id') . ', ';
        $info = $info . "}, ";
        $data =[];
        $info = $info . 'data: {';
        if ($request->has('sort')){
            $data["sort"] = $request->input('sort');
            $info = $info . 'sort => ' . $request->input('sort') . ', ';
        }
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

        $result = DB::table('attribute_value');
        if ($request->has('id'))
            $result = $result->where('attribute_value.id', '=', $request->input('id'));
        $result->delete();
        return $this->success();
    }
}
