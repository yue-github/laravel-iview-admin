<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Validator;

class SearchHistoryController extends Controller
{
    
    public function search(Request $request) {

        $validator = Validator::make($request->all(), [
            'offset' => 'required|integer|min:0',
            'length' => 'required|integer|min:1',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('search_history')
            ->select([
                'search_history.id as id',
                'search_history.user_id as user_id',
                'search_history.search_content as search_content',
            ]);
        $result = $result->where('search_history.user_id', '=', $this->token->id);
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
    public function create(Request $request) {

        $validator = Validator::make($request->all(), [
            'search_content' => 'string|max:100',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        DB::table('search_history')->insert([
            'user_id' => $this->token->id,
            'search_content' => $request->input('search_content'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        return $this->success();
    }
    public function delete_all(Request $request) {

        $validator = Validator::make($request->all(), [
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('search_history');
        $result = $result->where('search_history.user_id', '=', $this->token->id);
        $result->delete();
        return $this->success();
    }
    public function delete(Request $request) {

        $validator = Validator::make($request->all(), [
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('search_history');
        if ($request->has('id'))
            $result = $result->where('search_history.id', '=', $request->input('id'));
        $result->delete();
        return $this->success();
    }
}
