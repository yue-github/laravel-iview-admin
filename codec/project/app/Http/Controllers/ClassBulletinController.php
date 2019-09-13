<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Validator;

class ClassBulletinController extends Controller
{
    public function create(Request $request) {

        $validator = Validator::make($request->all(), [
            'class_id' => 'required|integer|min:0',
            'bulletin' => 'string|max:500',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        DB::table('class_bulletin')->insert([
            'class_id' => $request->input('class_id'),
            'bulletin' => $request->input('bulletin'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        return $this->success();
    }
    public function delete(Request $request) {

        $validator = Validator::make($request->all(), [
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('class_bulletin');
        if ($request->has('id'))
            $result = $result->where('class_bulletin.id', '=', $request->input('id'));
        $result->delete();
        return $this->success();
    }
    public function edit(Request $request) {

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|min:0',
            'class_id' => 'required|integer|min:0',
            'bulletin' => 'string|max:500',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('class_bulletin');
        $info = 'class_bulletinController->edit: ';
        if ($request->has('id'))
            $result = $result->where('class_bulletin.id', '=', $request->input('id'));
        $info = $info . 'with: {'; 
        if ($request->has('id')) 
            $info = $info . 'id => ' . $request->input('id') . ', '; 
        $info = $info . 'id => ' . $request->input('id') . ', ';
        $info = $info . "}, ";
        $data =[];
        $info = $info . 'data: {';
        if ($request->has('class_id')){
            $data["class_id"] = $request->input('class_id');
            $info = $info . 'class_id => ' . $request->input('class_id') . ', ';
        }
        if ($request->has('bulletin')){
            $data["bulletin"] = $request->input('bulletin');
            $info = $info . 'bulletin => ' . $request->input('bulletin') . ', ';
        }
        $data["updated_at"] = date('Y-m-d H:i:s');
        $info = $info . 'updated_at => ' . date('Y-m-d H:i:s') . ', ';
        $info = $info . "}";
        Log::info($info);
        $result->update($data);
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

        $result = DB::table('class_bulletin')
            ->select([
                'class_bulletin.id as id',
                'class_bulletin.bulletin as bulletin',
                'class_bulletin.created_at as created_at',
            ]);
        if ($request->has('class_id'))
            $result = $result->where('class_bulletin.class_id', '=', $request->input('class_id'));
        $result = $result->orderBy('class_bulletin.id', 'desc');
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
