<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Validator;

class SelectedController extends Controller
{
    
    public function search_group_by_user(Request $request) {

        $validator = Validator::make($request->all(), [
            'offset' => 'required|integer|min:0',
            'length' => 'required|integer|min:1',
            'selected_batch_id' => 'integer|min:0',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('selected')
            ->select([
                'selected.id as id',
                'selected.selected as selected',
                'users_user_id.id as user_id',
                'users_user_id.name as user_name',
                'users_user_id.idcard as user_idcard',
                'users_user_id.phone as user_phone',
                'users_user_id.company as user_company',
            ]);
        $result = $result->leftJoin('users as users_user_id', 'users_user_id.id', '=', 'selected.user_id');
        if ($request->has('selected_batch_id'))
            $result = $result->where('selected.selected_batch_id', '=', $request->input('selected_batch_id'));
        $result = $result->groupBy('selected.user_id');
        $result = $result->orderBy('selected.id', 'asc');
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
    
    public function search_group_by_product(Request $request) {

        $validator = Validator::make($request->all(), [
            'offset' => 'required|integer|min:0',
            'length' => 'required|integer|min:1',
            'selected_batch_id' => 'integer|min:0',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('selected')
            ->select([
                'selected.id as id',
                'selected.selected as selected',
                'products_product_id.id as product_id',
                'products_product_id.name as product_name',
                'products_product_id.period as product_period',
                'products_product_id.attr as product_attr',
                'products_product_id.price as product_price',
                'products_product_id.cer_year as product_cer_year',
                'purchased_product_id.is_first as is_first',
                'orders_order_id.owner_id as user_id',
            ]);
        $result = $result->leftJoin('products as products_product_id', 'products_product_id.id', '=', 'selected.product_id');
        $result = $result->leftJoin('purchased as purchased_product_id', 'purchased_product_id.product_id', '=', 'products_product_id.id');
        $result = $result->leftJoin('orders as orders_order_id', 'orders_order_id.id', '=', 'purchased_product_id.order_id');
        if ($request->has('selected_batch_id'))
            $result = $result->where('selected.selected_batch_id', '=', $request->input('selected_batch_id'));
        $result = $result->groupBy('selected.product_id');
        if ($request->has('user_id_arr'))
            $result = $result->whereIn('orders_order_id.owner_id', json_decode($request->input('user_id_arr')));
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
    
    public function search(Request $request) {

        $validator = Validator::make($request->all(), [
            'offset' => 'required|integer|min:0',
            'length' => 'required|integer|min:1',
            'user_id' => 'integer|min:0',
            'id' => 'integer|min:0',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('selected')
            ->select([
                'selected.id as id',
                'selected.selected as selected',
                'selected.user_id as user_id',
                'products_product_id.id as product_id',
                'products_product_id.name as product_name',
                'products_product_id.period as product_period',
                'products_product_id.price as product_price',
                'products_product_id.attr as product_attr',
                'products_product_id.cer_year as product_cer_year',
            ]);
        $result = $result->leftJoin('products as products_product_id', 'products_product_id.id', '=', 'selected.product_id');
        if ($request->has('user_id'))
            $result = $result->where('selected.user_id', '=', $request->input('user_id'));
        if ($request->has('id'))
            $result = $result->where('selected.id', '=', $request->input('id'));
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
    public function update(Request $request) {

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|min:0',
            'selected' => 'integer',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('selected');
        $info = 'selectedController->update: ';
        if ($request->has('id'))
            $result = $result->where('selected.id', '=', $request->input('id'));
        $info = $info . 'with: {'; 
        if ($request->has('id')) 
            $info = $info . 'id => ' . $request->input('id') . ', '; 
        $info = $info . 'id => ' . $request->input('id') . ', ';
        $info = $info . "}, ";
        $data =[];
        $info = $info . 'data: {';
        if ($request->has('selected')){
            $data["selected"] = $request->input('selected');
            $info = $info . 'selected => ' . $request->input('selected') . ', ';
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

        $result = DB::table('selected');
        if ($request->has('id'))
            $result = $result->where('selected.id', '=', $request->input('id'));
        $result->delete();
        return $this->success();
    }
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id_arr' => 'required',
            'product_id_arr' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $user_id_arr = $request->input('user_id_arr');
        $user_id_arr_json = json_decode($user_id_arr);
        $product_id_arr = $request->input('product_id_arr');
        $product_id_arr_json = json_decode($product_id_arr);
        $datas = array();
        foreach ($user_id_arr_json as $user_id) {
        foreach ($product_id_arr_json as $product_id) {
                $data = [
                    'user_id' => $user_id,
                    'product_id' => $product_id,
                ];
                array_push($datas, $data);
            }
        }
        $result = DB::table('selected')->insert($datas);
        if (!$result) {
            DB::rollBack();
            return $this->fails();
        }
        return $this->success();
    }
    public function create_by_idcard(Request $request) 
    {
        $result = \App\Utils\SelectedUtil::create_by_idcard($request);
        return $this->success($result);
    }
}
