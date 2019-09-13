<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Validator;

class CommentController extends Controller
{
    public function create(Request $request) {

        $validator = Validator::make($request->all(), [
            'discuss_theme_id' => 'integer|min:0',
            'content' => 'string',
            'product_id' => 'integer|min:0',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        DB::table('comment')->insert([
            'user_id' => $this->token->id,
            'discuss_theme_id' => $request->input('discuss_theme_id'),
            'content' => $request->input('content'),
            'product_id' => $request->input('product_id'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        return $this->success();
    }
    
    public function get_comment_by_theme(Request $request) {

        $validator = Validator::make($request->all(), [
            'offset' => 'required|integer|min:0',
            'length' => 'required|integer|min:1',
            'discuss_theme_id' => 'integer|min:0',
            'class_id' => 'integer|min:0',
            'user_id' => 'integer|min:0',
            'product_id' => 'integer|min:0',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('comment')
            ->select([
                'users_user_id.name as user_name',
                'comment.content as content',
                'comment.created_at as created_at',
            ]);
        $result = $result->leftJoin('users as users_user_id', 'users_user_id.id', '=', 'comment.user_id');
        if ($request->has('discuss_theme_id'))
            $result = $result->where('comment.discuss_theme_id', '=', $request->input('discuss_theme_id'));
        if ($request->has('class_id'))
            $result = $result->where('comment.class_id', '=', $request->input('class_id'));
        if ($request->has('user_id'))
            $result = $result->where('comment.user_id', '=', $request->input('user_id'));
        if ($request->has('product_id'))
            $result = $result->where('comment.product_id', '=', $request->input('product_id'));
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
