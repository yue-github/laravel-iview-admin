<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Validator;

class ShoppingCartController extends Controller
{
    public function create(Request $request) {

        $validator = Validator::make($request->all(), [
            'product_id' => 'required|integer|min:0',
            'shop_id' => 'integer|min:0',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        DB::table('shopping_cart')->insert([
            'user_id' => $this->token->id,
            'product_id' => $request->input('product_id'),
            'selected' => '0',
            'shop_id' => $request->input('shop_id'),
        ]);
        return $this->success();
    }
    
    public function search(Request $request) {

        $validator = Validator::make($request->all(), [
            'offset' => 'required|integer|min:0',
            'length' => 'required|integer|min:1',
            'shop_id' => 'integer|min:0',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('shopping_cart')
            ->select([
                'shopping_cart.id as id',
                'products_product_id.id as product_id',
                'products_product_id.name as product_name',
                'products_product_id.desc as product_desc',
                'products_product_id.price as product_price',
                'products_product_id.image as product_image',
                'shops_shop_id.name as shop_name',
                'shopping_cart.selected as selected',
                'shopping_cart.user_id as user_id',
            ]);
        $result = $result->leftJoin('products as products_product_id', 'products_product_id.id', '=', 'shopping_cart.product_id');
        $result = $result->leftJoin('shops as shops_shop_id', 'shops_shop_id.id', '=', 'products_product_id.shop_id');
        $result = $result->where('shopping_cart.user_id', '=', $this->token->id);
        if ($request->has('shop_id'))
            $result = $result->where('shopping_cart.shop_id', '=', $request->input('shop_id'));
        $result = $result->orderBy('shopping_cart.id', 'desc');
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
    public function edit(Request $request) {

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|min:0',
            'selected' => 'boolean',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('shopping_cart');
        $info = 'shopping_cartController->edit: ';
        if ($request->has('id'))
            $result = $result->where('shopping_cart.id', '=', $request->input('id'));
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

        $result = DB::table('shopping_cart');
        if ($request->has('id'))
            $result = $result->where('shopping_cart.id', '=', $request->input('id'));
        $result->delete();
        return $this->success();
    }
}
