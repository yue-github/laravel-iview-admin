<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Validator;

class AnswQuestionCommentController extends Controller
{
    
    public function search(Request $request) {

        $validator = Validator::make($request->all(), [
            'offset' => 'required|integer|min:0',
            'length' => 'required|integer|min:1',
            'answ_question_id' => 'integer|min:0',
            'user_id' => 'integer|min:0',
            'class_id' => 'integer|min:0',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('answ_question_comment')
            ->select([
                'answ_question_comment.id as id',
                'users_user_id.name as name',
                'answ_question_comment.content as content',
                'answ_question_comment.created_at as created_at',
            ]);
        $result = $result->leftJoin('users as users_user_id', 'users_user_id.id', '=', 'answ_question_comment.user_id');
        if ($request->has('answ_question_id'))
            $result = $result->where('answ_question_comment.answ_question_id', '=', $request->input('answ_question_id'));
        if ($request->has('user_id'))
            $result = $result->where('answ_question_comment.user_id', '=', $request->input('user_id'));
        if ($request->has('class_id'))
            $result = $result->where('answ_question_comment.class_id', '=', $request->input('class_id'));
        $result = $result->orderBy('answ_question_comment.created_at', 'desc');
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
            'answ_question_id' => 'integer|min:0',
            'content' => 'string',
            'class_id' => 'integer|min:0',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        DB::table('answ_question_comment')->insert([
            'answ_question_id' => $request->input('answ_question_id'),
            'user_id' => $this->token->id,
            'content' => $request->input('content'),
            'class_id' => $request->input('class_id'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        return $this->success();
    }
}
