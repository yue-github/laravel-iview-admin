<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Validator;

class PurchasedController extends Controller
{
    
    public function search_by_userId_and_packageId(Request $request) {

        $validator = Validator::make($request->all(), [
            'offset' => 'required|integer|min:0',
            'length' => 'required|integer|min:1',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('purchased')
            ->select([
                'orders_order_id.owner_id as user_id',
                'orders_order_id.state as state',
                'products_product_id.id as product_id',
                'products_product_id.name as product_name',
                'products_product_id.year as product_year',
                'projects_project_id.start_stydy_time as start_stydy_time',
                'projects_project_id.end_study_time as end_study_time',
                'package_package_id.id as package_id',
                'labels_id.name as label_name',
                'labels_parent_id.name as parent_name',
            ]);
        $result = $result->leftJoin('orders as orders_order_id', 'orders_order_id.id', '=', 'purchased.order_id');
        $result = $result->leftJoin('products as products_product_id', 'products_product_id.id', '=', 'purchased.product_id');
        $result = $result->leftJoin('projects as projects_project_id', 'projects_project_id.id', '=', 'products_product_id.project_id');
        $result = $result->leftJoin('package_product as package_product_product_id', 'package_product_product_id.product_id', '=', 'products_product_id.id');
        $result = $result->leftJoin('package as package_package_id', 'package_package_id.id', '=', 'package_product_product_id.package_id');
        $result = $result->leftJoin('product_labels as product_labels_product_id', 'product_labels_product_id.product_id', '=', 'products_product_id.id');
        $result = $result->leftJoin('labels as labels_label_id', 'labels_label_id.id', '=', 'product_labels_product_id.label_id');
        $result = $result->leftJoin('labels as labels_id', 'labels_id.id', '=', 'labels_label_id.id');
        $result = $result->leftJoin('labels as labels_parent_id', 'labels_parent_id.id', '=', 'labels_id.parent_id');
        $result = $result->where('orders_order_id.owner_id', '=', $this->token->id);
        if ($request->has('package_id'))
            $result = $result->where('package_package_id.id', '=', $request->input('package_id'));
        $result = $result->where('orders_order_id.state', '=', '2');
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
    
    public function get_rate_by_userid(Request $request) {

        $validator = Validator::make($request->all(), [
            'offset' => 'required|integer|min:0',
            'length' => 'required|integer|min:1',
            'id' => 'integer|min:0',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('purchased')
            ->select([
                'purchased.progress as progress',
                'purchased.rate as rate',
            ]);
        if ($request->has('id'))
            $result = $result->where('purchased.id', '=', $request->input('id'));
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
    public function batch_set_cer_year(Request $request) 
    {
        $result = \App\Utils\PurchasedUtil::batch_set_cer_year($request);
        return $this->success($result);
    }
    
    public function search(Request $request) {

        $validator = Validator::make($request->all(), [
            'offset' => 'required|integer|min:0',
            'length' => 'required|integer|min:1',
            'order_id' => 'integer|min:0',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('purchased')
            ->select([
                'purchased.id as id',
                'purchased.product_name as product_name',
                'purchased.price as price',
                'purchased.private as private',
                'purchased.score as score',
                'purchased.rate as rate',
                'purchased.updated_at as updated_at',
                'purchased.created_at as created_at',
                'purchased.is_first as is_first',
                'purchased.cer_year as purchased_cer_year',
                'products_product_id.id as product_id',
                'products_product_id.image as image',
                'products_product_id.period as period',
                'products_product_id.attr as attr',
                'products_product_id.cer_year as cer_year',
                'products_product_id.is_auth as is_auth',
                'shops_shop_id.name as shop_name',
            ]);
        $result = $result->leftJoin('products as products_product_id', 'products_product_id.id', '=', 'purchased.product_id');
        $result = $result->leftJoin('shops as shops_shop_id', 'shops_shop_id.id', '=', 'products_product_id.shop_id');
        $result = $result->leftJoin('orders as orders_owner_id', 'orders_owner_id.id', '=', 'purchased.order_id');
        if ($request->has('order_id'))
            $result = $result->where('purchased.order_id', '=', $request->input('order_id'));
        $result = $result->where('orders_owner_id.owner_id', '=', $this->token->id);
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
    
    public function search_pay_all(Request $request) {

        $validator = Validator::make($request->all(), [
            'offset' => 'required|integer|min:0',
            'length' => 'required|integer|min:1',
            'purchased_cer_year' => 'string|max:100',
            'score_ge' => 'integer',
            'progress' => 'numeric',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('purchased')
            ->select([
                'purchased.id as id',
                'purchased.price as price',
                'purchased.private as private',
                'purchased.score as score',
                'purchased.rate as rate',
                'purchased.updated_at as updated_at',
                'purchased.created_at as created_at',
                'purchased.is_first as is_first',
                'purchased.cer_year as purchased_cer_year',
                'purchased.progress as progress',
                'products_product_id.id as product_id',
                'products_product_id.name as product_name',
                'products_product_id.image as image',
                'products_product_id.period as period',
                'products_product_id.attr as attr',
                'products_product_id.cer_year as cer_year',
                'products_product_id.is_auth as is_auth',
                'products_product_id.is_project as is_project',
                'shops_shop_id.name as shop_name',
                'orders_order_id.state as state',
            ]);
        $result = $result->leftJoin('products as products_product_id', 'products_product_id.id', '=', 'purchased.product_id');
        $result = $result->leftJoin('shops as shops_shop_id', 'shops_shop_id.id', '=', 'products_product_id.shop_id');
        $result = $result->leftJoin('orders as orders_order_id', 'orders_order_id.id', '=', 'purchased.order_id');
        $result = $result->where('orders_order_id.owner_id', '=', $this->token->id);
        if ($request->has('product_attr'))
            $result = $result->where('products_product_id.attr', '=', $request->input('product_attr'));
        if ($request->has('products_cer_year'))
            $result = $result->where('products_product_id.cer_year', '=', $request->input('products_cer_year'));
        if ($request->has('products_is_auth'))
            $result = $result->where('products_product_id.is_auth', '=', $request->input('products_is_auth'));
        if ($request->has('purchased_cer_year'))
            $result = $result->where('purchased.cer_year', '=', $request->input('purchased_cer_year'));
        if ($request->has('score_ge'))
            $result = $result->where('purchased.score', '>=', $request->input('score_ge'));
        if ($request->has('progress'))
            $result = $result->where('purchased.progress', '=', $request->input('progress'));
        $result = $result->where('orders_order_id.state', '=', '2');
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
    
    public function shop_search_pay_all(Request $request) {

        $validator = Validator::make($request->all(), [
            'offset' => 'required|integer|min:0',
            'length' => 'required|integer|min:1',
            'purchased_cer_year' => 'string|max:100',
            'score_ge' => 'integer',
            'progress' => 'numeric',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('purchased')
            ->select([
                'purchased.id as id',
                'purchased.price as price',
                'purchased.private as private',
                'purchased.score as score',
                'purchased.rate as rate',
                'purchased.updated_at as updated_at',
                'purchased.created_at as created_at',
                'purchased.is_first as is_first',
                'purchased.cer_year as purchased_cer_year',
                'purchased.progress as progress',
                'products_product_id.id as product_id',
                'products_product_id.name as product_name',
                'products_product_id.image as image',
                'products_product_id.period as period',
                'products_product_id.attr as attr',
                'products_product_id.cer_year as cer_year',
                'products_product_id.is_auth as is_auth',
                'products_product_id.is_project as is_project',
                'shops_shop_id.name as shop_name',
                'orders_order_id.state as state',
            ]);
        $result = $result->leftJoin('products as products_product_id', 'products_product_id.id', '=', 'purchased.product_id');
        $result = $result->leftJoin('shops as shops_shop_id', 'shops_shop_id.id', '=', 'products_product_id.shop_id');
        $result = $result->leftJoin('orders as orders_order_id', 'orders_order_id.id', '=', 'purchased.order_id');
        if ($request->has('owner_id'))
            $result = $result->where('orders_order_id.owner_id', '=', $request->input('owner_id'));
        if ($request->has('product_attr'))
            $result = $result->where('products_product_id.attr', '=', $request->input('product_attr'));
        if ($request->has('products_cer_year'))
            $result = $result->where('products_product_id.cer_year', '=', $request->input('products_cer_year'));
        if ($request->has('products_is_auth'))
            $result = $result->where('products_product_id.is_auth', '=', $request->input('products_is_auth'));
        if ($request->has('purchased_cer_year'))
            $result = $result->where('purchased.cer_year', '=', $request->input('purchased_cer_year'));
        if ($request->has('score_ge'))
            $result = $result->where('purchased.score', '>=', $request->input('score_ge'));
        if ($request->has('progress'))
            $result = $result->where('purchased.progress', '=', $request->input('progress'));
        $result = $result->where('orders_order_id.state', '=', '2');
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
    
    public function search_pay_all_type_two(Request $request) {

        $validator = Validator::make($request->all(), [
            'offset' => 'required|integer|min:0',
            'length' => 'required|integer|min:1',
            'purchased_cer_year' => 'string|max:100',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('purchased')
            ->select([
                'purchased.id as id',
                'purchased.price as price',
                'purchased.private as private',
                'purchased.score as score',
                'purchased.rate as rate',
                'purchased.progress as progress',
                'purchased.updated_at as updated_at',
                'purchased.created_at as created_at',
                'purchased.is_first as is_first',
                'purchased.cer_year as purchased_cer_year',
                'products_product_id.id as product_id',
                'products_product_id.image as image',
                'products_product_id.name as product_name',
                'products_product_id.period as period',
                'products_product_id.attr as attr',
                'products_product_id.cer_year as cer_year',
                'products_product_id.is_auth as is_auth',
                'products_product_id.is_project as is_project',
                'shops_shop_id.name as shop_name',
                'orders_order_id.state as state',
            ]);
        $result = $result->leftJoin('products as products_product_id', 'products_product_id.id', '=', 'purchased.product_id');
        $result = $result->leftJoin('shops as shops_shop_id', 'shops_shop_id.id', '=', 'products_product_id.shop_id');
        $result = $result->leftJoin('orders as orders_order_id', 'orders_order_id.id', '=', 'purchased.order_id');
        $result = $result->where('orders_order_id.owner_id', '=', $this->token->id);
        if ($request->has('product_attr'))
            $result = $result->where('products_product_id.attr', '=', $request->input('product_attr'));
        if ($request->has('products_cer_year'))
            $result = $result->where('products_product_id.cer_year', '=', $request->input('products_cer_year'));
        if ($request->has('products_is_auth'))
            $result = $result->where('products_product_id.is_auth', '=', $request->input('products_is_auth'));
        if ($request->has('purchased_cer_year'))
            $result = $result->where('purchased.cer_year', '=', $request->input('purchased_cer_year'));
        $result->where(function ($query) use ($request){
        if ($request->has('score_lt'))
            $query->orWhere('purchased.score', '<', $request->input('score_lt'));
        if ($request->has('progress_lt'))
            $query->orWhere('purchased.progress', '<', $request->input('progress_lt'));
        });
        $result = $result->where('orders_order_id.state', '=', '2');
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
            'order_id' => 'integer|min:0',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('purchased')
            ->select([
                'purchased.id as id',
                'purchased.product_name as product_name',
                'purchased.price as price',
                'purchased.private as private',
                'purchased.score as score',
                'purchased.rate as rate',
                'purchased.updated_at as updated_at',
                'purchased.is_first as is_first',
                'products_product_id.id as product_id',
                'products_product_id.image as image',
                'products_product_id.period as period',
                'products_product_id.cer_year as cer_year',
                'products_product_id.is_auth as is_auth',
                'shops_shop_id.name as shop_name',
            ]);
        $result = $result->leftJoin('products as products_product_id', 'products_product_id.id', '=', 'purchased.product_id');
        $result = $result->leftJoin('shops as shops_shop_id', 'shops_shop_id.id', '=', 'products_product_id.shop_id');
        if ($request->has('order_id'))
            $result = $result->where('purchased.order_id', '=', $request->input('order_id'));
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
    public function sumbitResult(Request $request) {

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|min:0',
            'private' => 'json',
            'score' => 'integer',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('purchased');
        $info = 'purchasedController->sumbitResult: ';
        if ($request->has('id'))
            $result = $result->where('purchased.id', '=', $request->input('id'));
        $info = $info . 'with: {'; 
        if ($request->has('id')) 
            $info = $info . 'id => ' . $request->input('id') . ', '; 
        $info = $info . 'id => ' . $request->input('id') . ', ';
        $info = $info . "}, ";
        $data =[];
        $info = $info . 'data: {';
        if ($request->has('private')){
            $data["private"] = $request->input('private');
            $info = $info . 'private => ' . $request->input('private') . ', ';
        }
        if ($request->has('score')){
            $data["score"] = $request->input('score');
            $info = $info . 'score => ' . $request->input('score') . ', ';
        }
        $data["updated_at"] = date('Y-m-d H:i:s');
        $info = $info . 'updated_at => ' . date('Y-m-d H:i:s') . ', ';
        $info = $info . "}";
        Log::info($info);
        $result->update($data);
        return $this->success();
    }
    public function sumbitRate(Request $request) {

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|min:0',
            'rate' => 'json',
            'progress' => 'numeric',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('purchased');
        $info = 'purchasedController->sumbitRate: ';
        if ($request->has('id'))
            $result = $result->where('purchased.id', '=', $request->input('id'));
        $info = $info . 'with: {'; 
        if ($request->has('id')) 
            $info = $info . 'id => ' . $request->input('id') . ', '; 
        $info = $info . 'id => ' . $request->input('id') . ', ';
        $info = $info . "}, ";
        $data =[];
        $info = $info . 'data: {';
        if ($request->has('rate')){
            $data["rate"] = $request->input('rate');
            $info = $info . 'rate => ' . $request->input('rate') . ', ';
        }
        if ($request->has('progress')){
            $data["progress"] = $request->input('progress');
            $info = $info . 'progress => ' . $request->input('progress') . ', ';
        }
        $data["updated_at"] = date('Y-m-d H:i:s');
        $info = $info . 'updated_at => ' . date('Y-m-d H:i:s') . ', ';
        $info = $info . "}";
        Log::info($info);
        $result->update($data);
        return $this->success();
    }
    public function updateCerYear(Request $request) {

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|min:0',
            'is_first' => 'string|max:100',
            'cer_year' => 'string|max:100',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('purchased');
        $info = 'purchasedController->updateCerYear: ';
        if ($request->has('id'))
            $result = $result->where('purchased.id', '=', $request->input('id'));
        $info = $info . 'with: {'; 
        if ($request->has('id')) 
            $info = $info . 'id => ' . $request->input('id') . ', '; 
        $info = $info . 'id => ' . $request->input('id') . ', ';
        $info = $info . "}, ";
        $data =[];
        $info = $info . 'data: {';
        if ($request->has('is_first')){
            $data["is_first"] = $request->input('is_first');
            $info = $info . 'is_first => ' . $request->input('is_first') . ', ';
        }
        if ($request->has('cer_year')){
            $data["cer_year"] = $request->input('cer_year');
            $info = $info . 'cer_year => ' . $request->input('cer_year') . ', ';
        }
        $data["updated_at"] = date('Y-m-d H:i:s');
        $info = $info . 'updated_at => ' . date('Y-m-d H:i:s') . ', ';
        $info = $info . "}";
        Log::info($info);
        $result->update($data);
        return $this->success();
    }
    public function updateCerYears(Request $request) {

        $validator = Validator::make($request->all(), [
            'is_first' => 'string|max:100',
            'cer_year' => 'string|max:100',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('purchased');
        $info = 'purchasedController->updateCerYears: ';
        if ($request->has('id'))
            $result = $result->where('purchased.id', $request->input('id'));
        $info = $info . 'with: {'; 
        if ($request->has('id')) 
            $info = $info . 'id => ' . $request->input('id') . ', '; 
        if ($request->has('id_arr')){
            $result = $result->whereIn('id', json_decode($request->input('id_arr')));
            $info = $info . "id =>" . $request->input('id_arr');
        }
        $info = $info . "}, ";
        $data =[];
        $info = $info . 'data: {';
        if ($request->has('is_first')){
            $data["is_first"] = $request->input('is_first');
            $info = $info . 'is_first => ' . $request->input('is_first') . ', ';
        }
        if ($request->has('cer_year')){
            $data["cer_year"] = $request->input('cer_year');
            $info = $info . 'cer_year => ' . $request->input('cer_year') . ', ';
        }
        $data["updated_at"] = date('Y-m-d H:i:s');
        $info = $info . 'updated_at => ' . date('Y-m-d H:i:s') . ', ';
        $info = $info . "}";
        Log::info($info);
        $result->update($data);
        return $this->success();
    }
    
    public function get_more(Request $request) {

        $validator = Validator::make($request->all(), [
            'offset' => 'required|integer|min:0',
            'length' => 'required|integer|min:1',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('purchased')
            ->select([
                'purchased.id as id',
                'purchased.product_name as product_name',
                'purchased.updated_at as updated_at',
                'purchased.is_first as is_first',
                'products_product_id.id as product_id',
                'products_product_id.period as period',
                'products_product_id.attr as attr',
                'products_product_id.cer_year as cer_year',
            ]);
        $result = $result->leftJoin('products as products_product_id', 'products_product_id.id', '=', 'purchased.product_id');
        if ($request->has('ids'))
            $result = $result->whereIn('purchased.id', json_decode($request->input('ids')));
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
    
    public function get(Request $request) {

        $validator = Validator::make($request->all(), [
            'id' => 'integer|min:0',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('purchased')
            ->select([
                'purchased.id as id',
                'purchased.product_name as product_name',
                'purchased.price as price',
                'purchased.private as private',
                'purchased.score as score',
                'purchased.rate as rate',
                'purchased.updated_at as updated_at',
                'purchased.is_first as is_first',
                'purchased.progress as progress',
                'products_product_id.id as product_id',
                'products_product_id.image as image',
                'products_product_id.teacher as tearcher',
                'products_product_id.period as period',
                'products_product_id.attr as attr',
                'products_product_id.cer_year as cer_year',
                'shops_shop_id.name as shop_name',
            ]);
        $result = $result->leftJoin('products as products_product_id', 'products_product_id.id', '=', 'purchased.product_id');
        $result = $result->leftJoin('shops as shops_shop_id', 'shops_shop_id.id', '=', 'products_product_id.shop_id');
        if ($request->has('id'))
            $result = $result->where('purchased.id', '=', $request->input('id'));
        $result = $result->first();
        return $this->success($result);
    }
    public function batch_set_rate(Request $request) 
    {
        $result = \App\Utils\PurchasedUtil::batch_set_rate($request);
        return $this->success($result);
    }
    public function czUpload(Request $request) 
    {
        $result = \App\Utils\PurchasedUtil::czUpload($request);
        return $this->success($result);
    }
    public function get_image(Request $request) 
    {
        $result = \App\Utils\ImageUtil::getCerImg($request);
        return $this->success($result);
    }
}
