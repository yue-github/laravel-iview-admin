<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Validator;

class PackageAttributeValueController extends Controller
{
    
    public function search(Request $request) {

        $validator = Validator::make($request->all(), [
            'offset' => 'required|integer|min:0',
            'length' => 'required|integer|min:1',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('package_attribute_value')
            ->select([
                'package_attribute_value.package_id as package_id',
                'package_attribute_value.attribute_value_id as attribute_value_id',
            ]);
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
            'package_id' => 'integer|min:0',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('package_attribute_value')
            ->select([
                'package_attribute_value.id as id',
                'attribute_value_attribute_value_id.id as attribute_value_id',
                'attribute_value_attribute_value_id.name as attribute_value_name',
                'attribute_attribute_id.name as attribute_name',
            ]);
        $result = $result->leftJoin('attribute_value as attribute_value_attribute_value_id', 'attribute_value_attribute_value_id.id', '=', 'package_attribute_value.attribute_value_id');
        $result = $result->leftJoin('attribute_value_type as attribute_value_type_attribute_value_id', 'attribute_value_type_attribute_value_id.attribute_value_id', '=', 'attribute_value_attribute_value_id.id');
        $result = $result->leftJoin('attribute as attribute_attribute_id', 'attribute_attribute_id.id', '=', 'attribute_value_type_attribute_value_id.attribute_id');
        if ($request->has('package_id'))
            $result = $result->where('package_attribute_value.package_id', '=', $request->input('package_id'));
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
    public function shop_delete(Request $request) {

        $validator = Validator::make($request->all(), [
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('package_attribute_value');
        if ($request->has('id'))
            $result = $result->where('package_attribute_value.id', '=', $request->input('id'));
        $result->delete();
        return $this->success();
    }
    public function shop_create(Request $request) {

        $validator = Validator::make($request->all(), [
            'package_id' => 'integer|min:0',
            'attribute_value_id' => 'integer|min:0',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        DB::table('package_attribute_value')->insert([
            'package_id' => $request->input('package_id'),
            'attribute_value_id' => $request->input('attribute_value_id'),
        ]);
        return $this->success();
    }
}
