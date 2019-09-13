<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Validator;

class ProductLabelsController extends Controller
{
    
    public function listLabels(Request $request) {

        $validator = Validator::make($request->all(), [
            'offset' => 'required|integer|min:0',
            'length' => 'required|integer|min:1',
            'product_id' => 'integer|min:0',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('product_labels')
            ->select([
                'product_labels.id as id',
                'product_labels.product_id as product_id',
                'product_labels.label_id as label_id',
            ]);
        if ($request->has('product_id'))
            $result = $result->where('product_labels.product_id', '=', $request->input('product_id'));
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
    
    public function listProducts(Request $request) {

        $validator = Validator::make($request->all(), [
            'offset' => 'required|integer|min:0',
            'length' => 'required|integer|min:1',
            'label_id' => 'integer|min:0',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('product_labels')
            ->select([
                'product_labels.id as id',
                'products_product_id.id as product_id',
                'products_product_id.attr as attr',
                'products_product_id.is_auth as is_auth',
                'products_product_id.name as name',
                'products_product_id.period as period',
                'products_product_id.teacher as teacher',
                'products_product_id.image as image',
                'products_product_id.price as price',
                'products_product_id.cer_year as cer_year',
                'products_product_id.is_project as is_project',
                'projects_project_id.desc as project_desc',
                'shops_shop_id.id as shop_id',
                'shops_shop_id.name as shop_name',
                'product_labels.label_id as label_id',
            ]);
        $result = $result->leftJoin('products as products_product_id', 'products_product_id.id', '=', 'product_labels.product_id');
        $result = $result->leftJoin('projects as projects_project_id', 'projects_project_id.id', '=', 'products_product_id.project_id');
        $result = $result->leftJoin('shops as shops_shop_id', 'shops_shop_id.id', '=', 'products_product_id.shop_id');
        if ($request->has('label_id'))
            $result = $result->where('product_labels.label_id', '=', $request->input('label_id'));
        if ($request->has('attr'))
            $result = $result->where('products_product_id.attr', '=', $request->input('attr'));
        if ($request->has('is_auth'))
            $result = $result->where('products_product_id.is_auth', '=', $request->input('is_auth'));
        if ($request->has('cer_year'))
            $result = $result->where('products_product_id.cer_year', '=', $request->input('cer_year'));
        if ($request->has('shop_id'))
            $result = $result->where('shops_shop_id.id', '=', $request->input('shop_id'));
        if ($request->has('name'))
            $result = $result->where('products_product_id.name', 'like', '%'.$request->input('name').'%');
        if ($request->has('onsale'))
            $result = $result->where('products_product_id.onsale', '=', $request->input('onsale'));
        $result = $result->groupBy('product_labels.product_id');
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
    public function edit(Request $request) {

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|min:0',
            'label_id' => 'integer|min:0',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('product_labels');
        $info = 'product_labelsController->edit: ';
        if ($request->has('id'))
            $result = $result->where('product_labels.id', '=', $request->input('id'));
        $info = $info . 'with: {'; 
        if ($request->has('id')) 
            $info = $info . 'id => ' . $request->input('id') . ', '; 
        $info = $info . 'id => ' . $request->input('id') . ', ';
        $info = $info . "}, ";
        $data =[];
        $info = $info . 'data: {';
        if ($request->has('label_id')){
            $data["label_id"] = $request->input('label_id');
            $info = $info . 'label_id => ' . $request->input('label_id') . ', ';
        }
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

        $result = DB::table('product_labels');
        if ($request->has('id'))
            $result = $result->where('product_labels.id', '=', $request->input('id'));
        $result->delete();
        return $this->success();
    }
    
    public function id_list(Request $request) {

        $validator = Validator::make($request->all(), [
            'offset' => 'required|integer|min:0',
            'length' => 'required|integer|min:1',
            'label_id' => 'integer|min:0',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('product_labels')
            ->select([
                'product_labels.id as id',
            ]);
        if ($request->has('label_id'))
            $result = $result->where('product_labels.label_id', '=', $request->input('label_id'));
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
    public function delete_id(Request $request) {

        $validator = Validator::make($request->all(), [
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('product_labels');
        if ($request->has('id'))
            $result = $result->where('product_labels.id', $request->input('id'));
        $result->delete();
        return $this->success();
    }
}
