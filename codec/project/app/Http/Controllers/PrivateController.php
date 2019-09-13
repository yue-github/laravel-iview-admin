<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Validator;

class PrivateController extends Controller
{
    public function create(Request $request) {

        $validator = Validator::make($request->all(), [
            'content' => 'string',
            'resource_id' => 'integer|min:0',
            'purchased_id' => 'integer|min:0',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        DB::table('private')->insert([
            'content' => $request->input('content'),
            'resource_id' => $request->input('resource_id'),
            'purchased_id' => $request->input('purchased_id'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        return $this->success();
    }
    
    public function get(Request $request) {

        $validator = Validator::make($request->all(), [
            'resource_id' => 'integer|min:0',
            'purchased_id' => 'integer|min:0',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('private')
            ->select([
                'private.id as id',
                'private.content as content',
                'orders_order_id.owner_id as owner_id',
            ]);
        $result = $result->leftJoin('purchased as purchased_purchased_id', 'purchased_purchased_id.id', '=', 'private.purchased_id');
        $result = $result->leftJoin('orders as orders_order_id', 'orders_order_id.id', '=', 'purchased_purchased_id.order_id');
        $result = $result->where('orders_order_id.owner_id', '=', $this->token->id);
        if ($request->has('resource_id'))
            $result = $result->where('private.resource_id', '=', $request->input('resource_id'));
        if ($request->has('purchased_id'))
            $result = $result->where('private.purchased_id', '=', $request->input('purchased_id'));
        $result = $result->first();
        return $this->success($result);
    }
    
    public function get_by_purchased_id(Request $request) {

        $validator = Validator::make($request->all(), [
            'purchased_id' => 'integer|min:0',
            'resource_id' => 'integer|min:0',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('private')
            ->select([
                'private.id as id',
                'private.content as content',
            ]);
        if ($request->has('purchased_id'))
            $result = $result->where('private.purchased_id', '=', $request->input('purchased_id'));
        if ($request->has('resource_id'))
            $result = $result->where('private.resource_id', '=', $request->input('resource_id'));
        $result = $result->first();
        return $this->success($result);
    }
}
