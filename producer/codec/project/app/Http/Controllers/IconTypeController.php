<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Validator;

class IconTypeController extends Controller
{
    
    public function search(Request $request) {

        $validator = Validator::make($request->all(), [
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('icon_type')
            ->select([
                'icon_type.id as id',
                'icon_type.type as type',
            ]);
        $result = $result->get();
        return $this->success($result);
    }
}
