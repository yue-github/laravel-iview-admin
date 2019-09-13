<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Validator;

class AnswQuestionController extends Controller
{
    public function create(Request $request) {

        $validator = Validator::make($request->all(), [
            'title' => 'string|max:100',
            'content' => 'string',
            'product_id' => 'integer|min:0',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        DB::table('answ_question')->insert([
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            'user_id' => $this->token->id,
            'product_id' => $request->input('product_id'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        return $this->success();
    }
    
    public function search(Request $request) {

        $validator = Validator::make($request->all(), [
            'offset' => 'required|integer|min:0',
            'length' => 'required|integer|min:1',
            'product_id' => 'integer|min:0',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('answ_question')
            ->select([
                'answ_question.id as id',
                'answ_question.title as title',
                'answ_question.content as content',
                'users_user_id.name as user_name',
                'answ_question.created_at as created_at',
            ]);
        $result = $result->leftJoin('users as users_user_id', 'users_user_id.id', '=', 'answ_question.user_id');
        if ($request->has('product_id'))
            $result = $result->where('answ_question.product_id', '=', $request->input('product_id'));
        $result = $result->orderBy('answ_question.created_at', 'desc');
        $count = $result->count();
        $result = $result
            ->offset($request->input('offset'))
            ->limit($request->input('length'))
            ->get();
        
        for($result_i = 0; $result_i < count($result); $result_i++) {
            $answ_question_comment = DB::table('answ_question_comment')->select(['answ_question_comment.id as answ_id',
            ]);
        $answ_question_comment = $answ_question_comment->where('answ_question_id', $result[$result_i]->id);
        $answ_question_comment = $answ_question_comment->get();
        $result[$result_i]->answ_question_comment = $answ_question_comment;
        }
        $result = [
            'data' => $result,
            'total' => $count
        ];
        return $this->success($result);
    }
    
    public function search_by_instructor(Request $request) {

        $validator = Validator::make($request->all(), [
            'offset' => 'required|integer|min:0',
            'length' => 'required|integer|min:1',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('answ_question')
            ->select([
                'answ_question.id as id',
                'answ_question.title as title',
                'answ_question.content as content',
                'class_product_id.name as class_name',
            ]);
        $result = $result->leftJoin('products as products_product_id', 'products_product_id.id', '=', 'answ_question.product_id');
        $result = $result->leftJoin('class as class_product_id', 'class_product_id.product_id', '=', 'products_product_id.id');
        if ($request->has('instructor_id'))
            $result = $result->where('class_product_id.instructor_id', '=', $request->input('instructor_id'));
        if ($request->has('class_id'))
            $result = $result->where('class_product_id.id', '=', $request->input('class_id'));
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
