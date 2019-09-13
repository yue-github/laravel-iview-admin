<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Validator;

class PackageProductController extends Controller
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

        $result = DB::table('package_product')
            ->select([
                'products_product_id.id as product_id',
                'products_product_id.is_project as is_project',
                'products_product_id.name as product_name',
                'products_product_id.year as product_year',
                'projects_project_id.start_stydy_time as start_stydy_time',
                'projects_project_id.end_study_time as end_study_time',
            ]);
        $result = $result->leftJoin('products as products_product_id', 'products_product_id.id', '=', 'package_product.product_id');
        $result = $result->leftJoin('projects as projects_project_id', 'projects_project_id.id', '=', 'products_product_id.project_id');
        if ($request->has('package_id'))
            $result = $result->where('package_product.package_id', '=', $request->input('package_id'));
        $count = $result->count();
        $result = $result
            ->offset($request->input('offset'))
            ->limit($request->input('length'))
            ->get();
        
        for($result_i = 0; $result_i < count($result); $result_i++) {
            $product_labels = DB::table('product_labels')->select([                'labels_label_id.id as label_id',
                'labels_label_id.name as label_name',
                'labels_label_id.parent_id as label_parent_id',
            ]);
        $product_labels = $product_labels->leftJoin('labels as labels_label_id', 'labels_label_id.id', '=', 'product_labels.label_id');
        $product_labels = $product_labels->where('product_id', $result[$result_i]->product_id);
        $product_labels = $product_labels->get();
        $result[$result_i]->product_labels = $product_labels;
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
            'package_id' => 'integer|min:0',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('package_product')
            ->select([
                'products_product_id.id as product_id',
                'products_product_id.name as product_name',
            ]);
        $result = $result->leftJoin('products as products_product_id', 'products_product_id.id', '=', 'package_product.product_id');
        if ($request->has('package_id'))
            $result = $result->where('package_product.package_id', '=', $request->input('package_id'));
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

        $result = DB::table('package_product');
        if ($request->has('product_id'))
            $result = $result->where('package_product.product_id', '=', $request->input('product_id'));
        if ($request->has('package_id'))
            $result = $result->where('package_product.package_id', '=', $request->input('package_id'));
        $result->delete();
        return $this->success();
    }
    public function shop_create(Request $request) {

        $validator = Validator::make($request->all(), [
            'product_id' => 'integer|min:0',
            'package_id' => 'integer|min:0',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        DB::table('package_product')->insert([
            'product_id' => $request->input('product_id'),
            'package_id' => $request->input('package_id'),
        ]);
        return $this->success();
    }
}
