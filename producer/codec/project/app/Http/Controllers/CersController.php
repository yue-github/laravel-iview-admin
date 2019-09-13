<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Validator;

class CersController extends Controller
{
    public function create(Request $request) {

        $validator = Validator::make($request->all(), [
            'purchased_id' => 'json',
            'cer_year' => 'integer',
            'pay_date' => 'date',
            'end_date' => 'date',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        DB::table('cers')->insert([
            'purchased_id' => $request->input('purchased_id'),
            'cer_year' => $request->input('cer_year'),
            'pay_date' => $request->input('pay_date'),
            'end_date' => $request->input('end_date'),
            'owner_id' => $this->token->id,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        return $this->success();
    }
    public function shop_create(Request $request) {

        $validator = Validator::make($request->all(), [
            'purchased_id' => 'json',
            'cer_year' => 'integer',
            'pay_date' => 'date',
            'end_date' => 'date',
            'owner_id' => 'integer|min:0',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        DB::table('cers')->insert([
            'purchased_id' => $request->input('purchased_id'),
            'cer_year' => $request->input('cer_year'),
            'pay_date' => $request->input('pay_date'),
            'end_date' => $request->input('end_date'),
            'owner_id' => $request->input('owner_id'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        return $this->success();
    }
    
    public function search(Request $request) {

        $validator = Validator::make($request->all(), [
            'offset' => 'required|integer|min:0',
            'length' => 'required|integer|min:1',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('cers')
            ->select([
                'cers.purchased_id as purchased_id',
                'cers.cer_year as cer_year',
                'cers.pay_date as pay_date',
                'cers.end_date as end_date',
            ]);
        $result = $result->where('cers.owner_id', '=', $this->token->id);
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
            'owner_id' => 'integer|min:0',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('cers')
            ->select([
                'cers.purchased_id as purchased_id',
                'cers.cer_year as cer_year',
                'cers.pay_date as pay_date',
            ]);
        if ($request->has('owner_id'))
            $result = $result->where('cers.owner_id', '=', $request->input('owner_id'));
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
}
