<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Validator;

class ClassFileController extends Controller
{
    public function create(Request $request) {

        $validator = Validator::make($request->all(), [
            'class_id' => 'integer|min:0',
            'file_name' => 'string|max:100',
            'file_as_name' => 'string|max:100',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        DB::table('class_file')->insert([
            'class_id' => $request->input('class_id'),
            'file_name' => $request->input('file_name'),
            'file_as_name' => $request->input('file_as_name'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        return $this->success();
    }
    
    public function search(Request $request) {

        $validator = Validator::make($request->all(), [
            'offset' => 'required|integer|min:0',
            'length' => 'required|integer|min:1',
            'class_id' => 'integer|min:0',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('class_file')
            ->select([
                'class_file.id as id',
                'class_file.class_id as class_id',
                'class_file.file_name as file_name',
                'class_file.file_as_name as file_as_name',
            ]);
        if ($request->has('class_id'))
            $result = $result->where('class_file.class_id', '=', $request->input('class_id'));
        $result = $result->orderBy('class_file.id', 'desc');
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
    public function delete(Request $request) {

        $validator = Validator::make($request->all(), [
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('class_file');
        if ($request->has('id'))
            $result = $result->where('class_file.id', '=', $request->input('id'));
        $result->delete();
        return $this->success();
    }
}
