<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Validator;

class OrdersController extends Controller
{
    
    public function searchAdmin(Request $request) {

        $validator = Validator::make($request->all(), [
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('orders')
            ->select([
                'orders.owner_id as owner_id',
                'orders.state as state',
                'orders.total as total',
                'orders.invoice_title as invoice_title',
                'orders.tax_no as tax_no',
                'orders.code as code',
                'orders.created_at as created_at',
                'orders.pay_date as pay_date',
                'orders.cancel_date as cancel_date',
            ]);
        $result = $result->first();
        return $this->success($result);
    }
    public function export_excel(Request $request) {

        $validator = Validator::make($request->all(), [
            'offset' => 'required|integer|min:0',
            'length' => 'required|integer|min:1',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('orders')
            ->select([
                'users_owner_id.name as 用户名',
                'users_owner_id.idcard as 身份证号',
                'users_owner_id.phone as 电话号码',
                'orders.state as 订单状态',
                'orders.total as 订单总价',
                'orders.pay_type as 支付类型',
                'orders.invoice_title as 发票抬头',
                'orders.tax_no as 税号',
                'orders.invoice_type as 发票类型',
                'orders.invoice_email as 电子邮箱',
                'orders.invoice_address as 地址',
            ]);
        $result = $result->leftJoin('users as users_owner_id', 'users_owner_id.id', '=', 'orders.owner_id');
        if ($request->has('ids'))
            $result = $result->whereIn('orders.id', json_decode($request->input('ids')));
        if ($request->has('invoice_title_not_null'))
            $result = $result->whereNotNull('orders.invoice_title');
        if ($request->has('invoice_title_not_in'))
            $result = $result->whereNotIn('orders.invoice_title', json_decode($request->input('invoice_title_not_in')));
        $result = $result
            ->offset($request->input('offset'))
            ->limit($request->input('length'))
            ->get();
        
        //用于存储处理后的数据
        $exportData = array();
        array_push($exportData, array());
        //第一个数组存字段
        foreach ($result[0] as $key => $value) {
            array_push($exportData[0], iconv('UTF-8', 'GBK', $key));
        }
        //开始存数据
        for ($i = 0; $i < count($result); $i ++) {
            array_push($exportData, array());
            foreach ($result[$i] as $key => $value){
                array_push($exportData[$i + 1], iconv('UTF-8', 'GBK', $value) . '\t');
            }            
        }
        //文件被输出在public/export_excel
        $file_name = $request->input('file_name');
        \Maatwebsite\Excel\Facades\Excel::create($file_name, function($excel) use ($exportData){
            $excel->sheet('score', function($sheet) use ($exportData){
                $sheet->rows($exportData);
            });
        })->store('csv');
        return $this->success($file_name);
    }
    public function create(Request $request) {

        $validator = Validator::make($request->all(), [
            'pay_type' => 'required|integer',
            'invoice_title' => 'string|max:100',
            'tax_no' => 'string|max:100',
            'invoice_type' => 'string|max:100',
            'invoice_email' => 'string|email',
            'invoice_address' => 'string|max:200',
            'GEN_CODE' => 'string|max:200',
            'shop_id' => 'integer|min:0',
            'products' => 'required',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $products = DB::table("products")->whereIn("id", json_decode($request->input('products')));
        
        $parentId = DB::table('orders')
            ->insertGetId([
                'owner_id' => $this->token->id,
                'pay_type' => $request->input('pay_type'),
                'invoice_title' => $request->input('invoice_title'),
                'tax_no' => $request->input('tax_no'),
                'invoice_type' => $request->input('invoice_type'),
                'invoice_email' => $request->input('invoice_email'),
                'invoice_address' => $request->input('invoice_address'),
                'total' => $products->sum("price"),
                'code' => date('YmdHis').time().mt_rand(1000,9999),
                'shop_id' => $request->input('shop_id'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        $products = $products->get();

        for ($i = 0; $i < count($products); $i++) {
            DB::table('purchased')
                ->insert([
                    'order_id' => $parentId,
                    'product_id' => $products[$i]->id,
                    'product_name' => $products[$i]->name,
                    'price' => $products[$i]->price,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
        }
       
        return $this->success($parentId);
    }
//单个用户批量开课接口
    public function selected_create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer|min:0',
            'invoice_title' => 'string|max:32',
            'tax_no' => 'string|max:32',
            'invoice_type' => 'string|max:32',
            'invoice_email' => 'string|email',
            'invoice_address' => 'string|max:200',
            'GEN_CODE' => 'string|max:200',
            'pay_date' => 'date',
            'products' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $input_product_ids = json_decode($request->input('products'));

        //判断是否重复购买
        $pay_products = DB::table('orders')->select([
            'purchased.product_name as proudct_name',
            'purchased.product_id as product_id'
            ])
            ->leftJoin('purchased', 'purchased.order_id', '=', 'orders.id')
            ->where('orders.owner_id', '=', $request->input('user_id'))
            ->where('orders.state', '=', 2)
            ->whereIn('purchased.product_id', $input_product_ids)
            ->get();

        if($pay_products != null){
            return $this->fails($pay_products);
        }

        $products = DB::table("products")->whereIn("id", $input_product_ids);
        $parentId = DB::table('orders')
            ->insertGetId([
                'owner_id' => $request->input('user_id'),
                'pay_type' => 3,
                'invoice_title' => $request->input('invoice_title'),
                'tax_no' => $request->input('tax_no'),
                'invoice_type' => $request->input('invoice_type'),
                'invoice_email' => $request->input('invoice_email'),
                'invoice_address' => $request->input('invoice_address'),
                'total' => $products->sum("price"),
                'shop_id' => $this->token->shop_id,
                'code' => date('YmdHis') . time() . mt_rand(1000, 9999),
                'state' => 2,
                'pay_date' => $request->input('pay_date'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        $products = $products->get();

        for ($i = 0; $i < count($products); $i++) {
            DB::table('purchased')
                ->insert([
                    'order_id' => $parentId,
                    'product_id' => $products[$i]->id,
                    'product_name' => $products[$i]->name,
                    'price' => $products[$i]->price,
                    'is_first' => $request->input('is_first'), //TODO这里手写加is_first
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
        }
        return $this->success($parentId);
    }
    public function edit(Request $request) {

        $validator = Validator::make($request->all(), [
            'invoice_state' => 'integer',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('orders');
        $info = 'ordersController->edit: ';
        if ($request->has('id'))
            $result = $result->where('orders.id', $request->input('id'));
        $info = $info . 'with: {'; 
        if ($request->has('id')) 
            $info = $info . 'id => ' . $request->input('id') . ', '; 
        if ($request->has('ids')){
            $result = $result->whereIn('id', json_decode($request->input('ids')));
            $info = $info . "id =>" . $request->input('ids');
        }
        $info = $info . "}, ";
        $data =[];
        $info = $info . 'data: {';
        if ($request->has('invoice_state')){
            $data["invoice_state"] = $request->input('invoice_state');
            $info = $info . 'invoice_state => ' . $request->input('invoice_state') . ', ';
        }
        $data["updated_at"] = date('Y-m-d H:i:s');
        $info = $info . 'updated_at => ' . date('Y-m-d H:i:s') . ', ';
        $info = $info . "}";
        Log::info($info);
        $result->update($data);
        return $this->success();
    }
    //直接开课，传user_id_arr  product_ids
    public function batch_create(Request $request){
        $validator = Validator::make($request->all(), [
            'user_id_arr' => 'required',
            'product_ids' => 'required',
            'invoice_title' => 'string|max:32',
            'tax_no' => 'string|max:32',
            'invoice_type' => 'string|max:32',
            'invoice_email' => 'string|email',
            'invoice_address' => 'string|max:200',
            'GEN_CODE' => 'string|max:200',
            'pay_date' => 'date',
        ]);
        if ($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }
        DB::beginTransaction(); //开启事务

        //前端传进来的用户id
        $user_id_arr = json_decode($request->input('user_id_arr'));
        //前端传进来的产品id
        $input_product_ids = json_decode($request->input('product_ids'));

        foreach($user_id_arr as $user_id){
            //判断是否重复购买
            $pay_products = DB::table('orders')->select([
                    'purchased.product_name as proudct_name',
                    'purchased.product_id as product_id'
                    ])
                    ->leftJoin('purchased', 'purchased.order_id', '=', 'orders.id')
                    ->where('orders.owner_id', '=', $user_id)
                    ->where('orders.state', '=', 2)
                    ->whereIn('purchased.product_id', $input_product_ids)
                    ->get();
            if($pay_products != null){
                DB::rollback();
                return $this->fails($pay_products);
            }
            $products = DB::table("products")->whereIn("id", $input_product_ids);
            $parentId = DB::table('orders')
                ->insertGetId([
                    'owner_id' => $user_id,
                    'pay_type' => 3,
                    'invoice_title' => $request->input('invoice_title'),
                    'tax_no' => $request->input('tax_no'),
                    'invoice_type' => $request->input('invoice_type'),
                    'invoice_email' => $request->input('invoice_email'),
                    'invoice_address' => $request->input('invoice_address'),
                    'total' => $products->sum("price"),
                    'shop_id' => $this->token->shop_id,
                    'code' => date('YmdHis') . time() . mt_rand(1000, 9999),
                    'state' => 2,
                    'pay_date' => $request->input('pay_date'),
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            $products = $products->get();
    
            for ($i = 0; $i < count($products); $i++) {
                DB::table('purchased')
                    ->insert([
                        'order_id' => $parentId,
                        'product_id' => $products[$i]->id,
                        'product_name' => $products[$i]->name,
                        'price' => $products[$i]->price,
                        'is_first' => $request->input('is_first'),
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
            }
        }
        DB::commit();
        return $this->success();
    }
    public function alipay(Request $request) {
    
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|min:0',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $data = DB::table('orders')->where('id', $request->input('id'))->first();
        if(!$data) {
            return $this->fails('订单不存在');
        }
        $body = '购物订单';
        $subject = '专业课程云超市';
        $total_amount = $data->total;
        $out_trade_no = $data->code;
        $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $notify_url = $protocol . $_SERVER["HTTP_HOST"] . '/orders/alipay_callback';
        $return_url = $protocol . $_SERVER["HTTP_HOST"];
        $result = \App\Utils\AlipayUtil::execute(['body'=>$body, 'subject'=>$subject, 'total_amount'=>$total_amount, 'out_trade_no'=>$out_trade_no, 'notify_url'=>$notify_url, 'return_url'=>$return_url]);
        if($result) {
            DB::table('orders')->where('id', $data->id)->update(['out_trade_no'=>$out_trade_no]);
            return $this->success($result);
        } else {
            return $this->fails();
        }
    }
    public function alipay_callback(Request $request) {
    
        $validator = Validator::make($request->all(), [
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = \App\Utils\AlipayUtil::callback($request->all(), config('alipay'));
        if($result) {
            $out_trade_no = $result['out_trade_no'];
            $data = DB::table('orders')->where('out_trade_no', $out_trade_no)->first();
            if($data) {
                DB::table('orders')->where('out_trade_no', $out_trade_no)->update(['state'=>2, 'pay_date'=>date('Y-m-d H:i:s')]);
            }else{
                \Illuminate\Support\Facades\Log::info('不存在单号： ' . $out_trade_no);
            }
        }else{
            \Illuminate\Support\Facades\Log::info('回调异常' . $result);
        }
    }
    public function wxpay(Request $request) {
    
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|min:0',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $data = DB::table('orders')->where('id', $request->input('id'))->first();
        if(!$data) {
            return $this->fails('订单不存在');
        }
        $body = '购物订单';
        $total_fee = $data->total;
        $out_trade_no = $data->code;
        $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $notify_url = $protocol . $_SERVER["HTTP_HOST"] . '/orders/wxpay_callback';
        $result = \App\Utils\WxpayUtil::execute(['body'=>$body, 'time_start' => date('Y-m-d H:i:s'), 'total_fee'=>$total_fee, 'out_trade_no'=>$out_trade_no, 'notify_url'=>$notify_url]);
        if($result) {
            DB::table('orders')->where('id', $data->id)->update(['out_trade_no'=>$out_trade_no]);
            return $this->success($result);
        } else {
            return $this->fails();
        }
    }
    public function wxpay_callback(Request $request) {
    
        $validator = Validator::make($request->all(), [
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = \App\Utils\WxpayUtil::callBack($request->all(), config('wxpay'));
        if($result) {
            $out_trade_no = $result['out_trade_no'];
            $data = DB::table('orders')->where('out_trade_no', $out_trade_no)->first();
            if($data) {
                DB::table('orders')->where('out_trade_no', $out_trade_no)->update(['state'=>2, 'pay_date'=>date('Y-m-d H:i:s')]);
            }else{
                \Illuminate\Support\Facades\Log::info('不存在单号： ' . $out_trade_no);
            }
        }else{
            \Illuminate\Support\Facades\Log::info('回调异常' . $result);
        }
    }
public function wxpay_js(Request $request) {
    
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|min:0',
        ]);
        if($validator->fails()) {
            return $this->fails( $validator->errors()->all());
        }

        $data = DB::table('orders')->where('id', $request->input('id'))->first();
        if(!$data) {
            return $this->fails('订单不存在');
        }
        $open_id = DB::table('users')->select('open_id')->where('id', '=', $this->token->id)->first()->open_id;
        $body = '购物订单';
        $total_fee = $data->total;
        $out_trade_no = $data->code;
        $notify_url = 'https://' . $_SERVER["HTTP_HOST"] . '/orders/wxpay_js_callback';
        $result = \App\Utils\WxpayUtil::execute_js(['body'=>$body, 'time_start' => date('Y-m-d H:i:s'), 'total_fee'=>$total_fee, 'out_trade_no'=>$out_trade_no, 'notify_url'=>$notify_url], $open_id);
        if($result) {
            DB::table('orders')->where('id', $data->id)->update(['out_trade_no'=>$out_trade_no]);
            return $this->success($result);
        } else {
            return $this->fails();
        }
    }
    public function wxpay_js_callback(Request $request) {
    
        $validator = Validator::make($request->all(), [
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = \App\Utils\WxpayUtil::jsCallBack($request->all(), config('wxpay'));
        if($result) {
            $out_trade_no = $result['out_trade_no'];
            $data = DB::table('orders')->where('out_trade_no', $out_trade_no)->first();
            if($data) {
                DB::table('orders')->where('out_trade_no', $out_trade_no)->update(['state'=>2, 'pay_date'=>date('Y-m-d H:i:s')]);
            }else{
                \Illuminate\Support\Facades\Log::info('不存在单号： ' . $out_trade_no);
            }
        }else{
            \Illuminate\Support\Facades\Log::info('回调异常' . $result);
        }
    }
    
    public function search(Request $request) {

        $validator = Validator::make($request->all(), [
            'offset' => 'required|integer|min:0',
            'length' => 'required|integer|min:1',
            'state' => 'integer',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('orders')
            ->select([
                'orders.id as id',
                'orders.owner_id as owner_id',
                'orders.state as state',
                'orders.total as total',
                'orders.pay_type as pay_type',
                'orders.pay_date as pay_date',
                'orders.invoice_title as invoice_title',
                'orders.tax_no as tax_no',
                'orders.invoice_type as invoice_type',
                'orders.invoice_email as invoice_email',
                'orders.invoice_address as invoice_address',
                'orders.code as code',
                'orders.created_at as created_at',
            ]);
        $result = $result->where('orders.owner_id', '=', $this->token->id);
        if ($request->has('state'))
            $result = $result->where('orders.state', '=', $request->input('state'));
        $result = $result->orderBy('orders.code', 'desc');
        $count = $result->count();
        $result = $result
            ->offset($request->input('offset'))
            ->limit($request->input('length'))
            ->get();
        
        for($result_i = 0; $result_i < count($result); $result_i++) {
            $purchased = DB::table('purchased')->select(['purchased.product_name as product_name',
                'products_product_id.id as product_id',
                'products_product_id.image as image',
                'products_product_id.period as period',
                'products_product_id.price as price',
                'shops_shop_id.name as shop_name',
            ]);
        $purchased = $purchased->leftJoin('products as products_product_id', 'products_product_id.id', '=', 'purchased.product_id');
        $purchased = $purchased->leftJoin('shops as shops_shop_id', 'shops_shop_id.id', '=', 'products_product_id.shop_id');
        $purchased = $purchased->where('order_id', $result[$result_i]->id);
        $purchased = $purchased->get();
        $result[$result_i]->purchased = $purchased;
        }
        $result = [
            'data' => $result,
            'total' => $count
        ];
        return $this->success($result);
    }
    
    public function searchByUserId(Request $request) {

        $validator = Validator::make($request->all(), [
            'offset' => 'required|integer|min:0',
            'length' => 'required|integer|min:1',
            'user_id' => 'integer|min:0',
            'state' => 'integer',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('orders')
            ->select([
                'orders.id as id',
                'orders.owner_id as owner_id',
                'orders.state as state',
                'orders.total as total',
                'orders.pay_type as pay_type',
                'orders.invoice_title as invoice_title',
                'orders.tax_no as tax_no',
                'orders.invoice_type as invoice_type',
                'orders.invoice_email as invoice_email',
                'orders.invoice_address as invoice_address',
                'orders.code as code',
                'orders.created_at as created_at',
            ]);
        if ($request->has('user_id'))
            $result = $result->where('orders.owner_id', '=', $request->input('user_id'));
        if ($request->has('state'))
            $result = $result->where('orders.state', '=', $request->input('state'));
        $result = $result->orderBy('orders.code', 'desc');
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
    
    public function searchCourses(Request $request) {

        $validator = Validator::make($request->all(), [
            'offset' => 'required|integer|min:0',
            'length' => 'required|integer|min:1',
            'state' => 'integer',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('orders')
            ->select([
                'purchased_order_id.product_id as product_id',
                'purchased_order_id.product_name as product_name',
                'purchased_order_id.price as price',
                'purchased_order_id.private as private',
            ]);
        $result = $result->leftJoin('purchased as purchased_order_id', 'purchased_order_id.order_id', '=', 'orders.id');
        $result = $result->where('orders.owner_id', '=', $this->token->id);
        if ($request->has('state'))
            $result = $result->where('orders.state', '=', $request->input('state'));
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
    public function cancel(Request $request) {

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|min:0',
            'state' => 'required|integer',
            'pay_date' => 'date',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('orders');
        $info = 'ordersController->cancel: ';
        if ($request->has('id'))
            $result = $result->where('orders.id', '=', $request->input('id'));
        $info = $info . 'with: {'; 
        if ($request->has('id')) 
            $info = $info . 'id => ' . $request->input('id') . ', '; 
        $info = $info . 'id => ' . $request->input('id') . ', ';
        $info = $info . "}, ";
        $data =[];
        $info = $info . 'data: {';
        if ($request->has('state')){
            $data["state"] = $request->input('state');
            $info = $info . 'state => ' . $request->input('state') . ', ';
        }
        $data["cancel_date"] = date('Y-m-d H:i:s');
            $info = $info . 'cancel_date => ' . date('Y-m-d H:i:s') . ', ';
        if ($request->has('pay_date')){
            $data["pay_date"] = $request->input('pay_date');
            $info = $info . 'pay_date => ' . $request->input('pay_date') . ', ';
        }
        $data["updated_at"] = date('Y-m-d H:i:s');
        $info = $info . 'updated_at => ' . date('Y-m-d H:i:s') . ', ';
        $info = $info . "}";
        Log::info($info);
        $result->update($data);
        return $this->success();
    }
    
    public function get(Request $request) {

        $validator = Validator::make($request->all(), [
            'id' => 'integer|min:0',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('orders')
            ->select([
                'orders.id as id',
                'orders.state as state',
                'orders.total as total',
                'orders.pay_type as pay_type',
                'orders.invoice_title as invoice_title',
                'orders.tax_no as tax_no',
                'orders.invoice_type as invoice_type',
                'orders.invoice_email as invoice_email',
                'orders.invoice_address as invoice_address',
                'orders.code as code',
                'orders.created_at as created_at',
                'orders.pay_date as pay_date',
                'orders.cancel_date as cancel_date',
            ]);
        if ($request->has('id'))
            $result = $result->where('orders.id', '=', $request->input('id'));
        $result = $result->first();
            $item = DB::table('purchased')->select(['purchased.product_name as product_name',
                'products_product_id.id as product_id',
                'products_product_id.period as period',
                'products_product_id.price as price',
                'products_product_id.image as image',
                'shops_shop_id.name as shop_name',
            ]);
        $item = $item->leftJoin('products as products_product_id', 'products_product_id.id', '=', 'purchased.product_id');
        $item = $item->leftJoin('shops as shops_shop_id', 'shops_shop_id.id', '=', 'products_product_id.shop_id');
        $item = $item->where('order_id', $result->id);
        $result->purchased = $item->get();
        return $this->success($result);
    }
    
    public function shop_search(Request $request) {

        $validator = Validator::make($request->all(), [
            'offset' => 'required|integer|min:0',
            'length' => 'required|integer|min:1',
            'state' => 'integer',
            'pay_type' => 'integer',
            'invoice_type' => 'string|max:100',
            'code' => 'string|max:200',
            'invoice_state' => 'integer',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('orders')
            ->select([
                'orders.id as id',
                'users_owner_id.name as user_name',
                'users_owner_id.idcard as idcard',
                'users_owner_id.phone as phone',
                'orders.state as state',
                'orders.total as total',
                'orders.pay_date as pay_date',
                'orders.pay_type as pay_type',
                'orders.invoice_title as invoice_title',
                'orders.invoice_state as invoice_state',
                'orders.tax_no as tax_no',
                'orders.invoice_type as invoice_type',
                'orders.invoice_email as invoice_email',
                'orders.invoice_address as invoice_address',
                'orders.code as code',
                'orders.created_at as created_at',
            ]);
        $result = $result->leftJoin('users as users_owner_id', 'users_owner_id.id', '=', 'orders.owner_id');
        $result = $result->where('orders.shop_id', '=', $this->token->shop_id);
        if ($request->has('state'))
            $result = $result->where('orders.state', '=', $request->input('state'));
        if ($request->has('pay_type'))
            $result = $result->where('orders.pay_type', '=', $request->input('pay_type'));
        if ($request->has('invoice_type'))
            $result = $result->where('orders.invoice_type', '=', $request->input('invoice_type'));
        if ($request->has('code'))
            $result = $result->where('orders.code', '=', $request->input('code'));
        if ($request->has('user_name'))
            $result = $result->where('users_owner_id.name', 'like', '%'.$request->input('user_name').'%');
        if ($request->has('invoice_state'))
            $result = $result->where('orders.invoice_state', '=', $request->input('invoice_state'));
        if ($request->has('invoice_title_not_null'))
            $result = $result->whereNotNull('orders.invoice_title');
        if ($request->has('invoice_title_not_in'))
            $result = $result->whereNotIn('orders.invoice_title', json_decode($request->input('invoice_title_not_in')));
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
    
    public function shop_get(Request $request) {

        $validator = Validator::make($request->all(), [
            'id' => 'integer|min:0',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('orders')
            ->select([
                'orders.owner_id as owner_id',
                'orders.state as state',
                'orders.total as total',
                'orders.pay_type as pay_type',
                'orders.invoice_title as invoice_title',
                'orders.tax_no as tax_no',
                'orders.invoice_type as invoice_type',
                'orders.invoice_email as invoice_email',
                'orders.invoice_address as invoice_address',
                'orders.code as code',
                'orders.created_at as created_at',
                'orders.pay_date as pay_date',
                'orders.cancel_date as cancel_date',
            ]);
        if ($request->has('id'))
            $result = $result->where('orders.id', '=', $request->input('id'));
        $result = $result->first();
        return $this->success($result);
    }
    
    public function get_money(Request $request) {

        $validator = Validator::make($request->all(), [
            'code' => 'string|max:200',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('orders')
            ->select([
                'orders.total as total',
            ]);
        if ($request->has('code'))
            $result = $result->where('orders.code', '=', $request->input('code'));
        $result = $result->first();
        return $this->success($result);
    }
    
    public function get_money_token(Request $request) {

        $validator = Validator::make($request->all(), [
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('orders')
            ->select([
                'orders.total as total',
            ]);
        $result = $result->where('orders.id', '=', $this->token->id);
        $result = $result->first();
        return $this->success($result);
    }
    public function edit_state(Request $request) {

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|min:0',
            'state' => 'required|integer',
            'pay_type' => 'required|integer',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('orders');
        $info = 'ordersController->edit_state: ';
        if ($request->has('id'))
            $result = $result->where('orders.id', '=', $request->input('id'));
        $info = $info . 'with: {'; 
        if ($request->has('id')) 
            $info = $info . 'id => ' . $request->input('id') . ', '; 
        $info = $info . 'id => ' . $request->input('id') . ', ';
        $info = $info . "}, ";
        $data =[];
        $info = $info . 'data: {';
        if ($request->has('state')){
            $data["state"] = $request->input('state');
            $info = $info . 'state => ' . $request->input('state') . ', ';
        }
        if ($request->has('pay_type')){
            $data["pay_type"] = $request->input('pay_type');
            $info = $info . 'pay_type => ' . $request->input('pay_type') . ', ';
        }
        $data["updated_at"] = date('Y-m-d H:i:s');
        $info = $info . 'updated_at => ' . date('Y-m-d H:i:s') . ', ';
        $info = $info . "}";
        Log::info($info);
        $result->update($data);
        return $this->success();
    }
}
