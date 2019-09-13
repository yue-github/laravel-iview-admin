<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Validator;

class AttributeValueTypeController extends Controller
{
    public function shop_delete(Request $request) {

        $validator = Validator::make($request->all(), [
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('attribute_value_type');
        if ($request->has('id'))
            $result = $result->where('attribute_value_type.attribute_value_id', '=', $request->input('id'));
        $result->delete();
        return $this->success();
    }
}
