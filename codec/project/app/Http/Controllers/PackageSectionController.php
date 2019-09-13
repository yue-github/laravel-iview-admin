<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Validator;

class PackageSectionController extends Controller
{
    public function shop_create(Request $request) {

        $validator = Validator::make($request->all(), [
            'package_id' => 'integer|min:0',
            'section_id' => 'integer|min:0',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        DB::table('package_section')->insert([
            'package_id' => $request->input('package_id'),
            'section_id' => $request->input('section_id'),
        ]);
        return $this->success();
    }
    public function shop_delete(Request $request) {

        $validator = Validator::make($request->all(), [
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('package_section');
        if ($request->has('id'))
            $result = $result->where('package_section.id', '=', $request->input('id'));
        $result->delete();
        return $this->success();
    }
    
    public function shop_search(Request $request) {

        $validator = Validator::make($request->all(), [
            'offset' => 'required|integer|min:0',
            'length' => 'required|integer|min:1',
            'section_id' => 'integer|min:0',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('package_section')
            ->select([
                'package_section.id as id',
                'package_package_id.name as package_name',
            ]);
        $result = $result->leftJoin('package as package_package_id', 'package_package_id.id', '=', 'package_section.package_id');
        if ($request->has('section_id'))
            $result = $result->where('package_section.section_id', '=', $request->input('section_id'));
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
