<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Validator;

class ProportionController extends Controller
{
    public function create(Request $request) {

        $validator = Validator::make($request->all(), [
            'resources' => 'string',
            'product_id' => 'integer|min:0',
            'shop_id' => 'integer|min:0',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        DB::table('proportion')->insert([
            'resources' => $request->input('resources'),
            'product_id' => $request->input('product_id'),
            'shop_id' => $request->input('shop_id'),
        ]);
        return $this->success();
    }
    
    public function get(Request $request) {

        $validator = Validator::make($request->all(), [
            'product_id' => 'integer|min:0',
            'shop_id' => 'integer|min:0',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('proportion')
            ->select([
                'proportion.resources as resources',
            ]);
        if ($request->has('product_id'))
            $result = $result->where('proportion.product_id', '=', $request->input('product_id'));
        if ($request->has('shop_id'))
            $result = $result->where('proportion.shop_id', '=', $request->input('shop_id'));
        $result = $result->first();
        return $this->success($result);
    }
    public function edit(Request $request) {

        $validator = Validator::make($request->all(), [
            'product_id' => 'integer|min:0',
            'shop_id' => 'integer|min:0',
            'resources' => 'string',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('proportion');
        $info = 'proportionController->edit: ';
        if ($request->has('product_id'))
            $result = $result->where('proportion.product_id', '=', $request->input('product_id'));
        if ($request->has('shop_id'))
            $result = $result->where('proportion.shop_id', '=', $request->input('shop_id'));
        $info = $info . 'with: {'; 
        if ($request->has('id')) 
            $info = $info . 'id => ' . $request->input('id') . ', '; 
        $info = $info . 'product_id => ' . $request->input('product_id') . ', ';
        $info = $info . 'shop_id => ' . $request->input('shop_id') . ', ';
        $info = $info . "}, ";
        $data =[];
        $info = $info . 'data: {';
        if ($request->has('resources')){
            $data["resources"] = $request->input('resources');
            $info = $info . 'resources => ' . $request->input('resources') . ', ';
        }
        $info = $info . "}";
        Log::info($info);
        $result->update($data);
        return $this->success();
    }
}
