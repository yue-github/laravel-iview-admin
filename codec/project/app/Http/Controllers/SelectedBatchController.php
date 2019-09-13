<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Validator;

class SelectedBatchController extends Controller
{
    
    public function search(Request $request) {

        $validator = Validator::make($request->all(), [
            'offset' => 'required|integer|min:0',
            'length' => 'required|integer|min:1',
            'remarks' => 'string|max:100',
            'state' => 'integer',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('selected_batch')
            ->select([
                'selected_batch.batch_num as batch_num',
                'selected_batch.remarks as remarks',
                'selected_batch.state as state',
                'selected_batch.id as id',
                'selected_batch.created_at as created_at',
            ]);
        if ($request->has('remarks'))
            $result = $result->where('selected_batch.remarks', 'like', '%'.$request->input('remarks').'%');
        if ($request->has('state'))
            $result = $result->where('selected_batch.state', '=', $request->input('state'));
        if ($request->has('created_at'))
            $result = $result->where('selected_batch.created_at', 'like', '%'.$request->input('created_at').'%');
        $result = $result->orderBy('selected_batch.created_at', 'desc');
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
            'remarks' => 'string|max:100',
            'state' => 'integer',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('selected_batch');
        $info = 'selected_batchController->edit: ';
        if ($request->has('id'))
            $result = $result->where('selected_batch.id', '=', $request->input('id'));
        $info = $info . 'with: {'; 
        if ($request->has('id')) 
            $info = $info . 'id => ' . $request->input('id') . ', '; 
        $info = $info . 'id => ' . $request->input('id') . ', ';
        $info = $info . "}, ";
        $data =[];
        $info = $info . 'data: {';
        if ($request->has('remarks')){
            $data["remarks"] = $request->input('remarks');
            $info = $info . 'remarks => ' . $request->input('remarks') . ', ';
        }
        if ($request->has('state')){
            $data["state"] = $request->input('state');
            $info = $info . 'state => ' . $request->input('state') . ', ';
        }
        $data["updated_at"] = date('Y-m-d H:i:s');
        $info = $info . 'updated_at => ' . date('Y-m-d H:i:s') . ', ';
        $info = $info . "}";
        Log::info($info);
        $result->update($data);
        return $this->success();
    }
    public function create(Request $request) {

        $validator = Validator::make($request->all(), [
            'batch_num' => 'string|max:100',
            'remarks' => 'string|max:100',
            'state' => 'integer',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        DB::table('selected_batch')->insert([
            'batch_num' => $request->input('batch_num'),
            'remarks' => $request->input('remarks'),
            'state' => $request->input('state'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        return $this->success();
    }
}
