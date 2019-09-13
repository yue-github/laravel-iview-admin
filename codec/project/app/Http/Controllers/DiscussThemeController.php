<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Validator;

class DiscussThemeController extends Controller
{
    public function delete(Request $request) {

        $validator = Validator::make($request->all(), [
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('discuss_theme');
        if ($request->has('resource_id'))
            $result = $result->where('discuss_theme.resource_id', '=', $request->input('resource_id'));
        $result->delete();
        return $this->success();
    }
    public function create(Request $request) {

        $validator = Validator::make($request->all(), [
            'content' => 'string|max:100',
            'sort' => 'integer',
            'title' => 'string|max:100',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        DB::table('discuss_theme')->insert([
            'content' => $request->input('content'),
            'sort' => $request->input('sort'),
            'title' => $request->input('title'),
            'owner_id' => $this->token->id,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        return $this->success();
    }
    
    public function search(Request $request) {

        $validator = Validator::make($request->all(), [
            'offset' => 'required|integer|min:0',
            'length' => 'required|integer|min:1',
            'title' => 'string|max:100',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('discuss_theme')
            ->select([
                'discuss_theme.id as id',
                'discuss_theme.content as content',
                'discuss_theme.sort as sort',
                'discuss_theme.title as title',
                'discuss_theme.resource_id as resource_id',
            ]);
        if ($request->has('title'))
            $result = $result->where('discuss_theme.title', 'like', '%'.$request->input('title').'%');
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
    
    public function get(Request $request) {

        $validator = Validator::make($request->all(), [
            'id' => 'integer|min:0',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('discuss_theme')
            ->select([
                'discuss_theme.content as content',
                'discuss_theme.sort as sort',
                'discuss_theme.title as title',
            ]);
        if ($request->has('id'))
            $result = $result->where('discuss_theme.id', '=', $request->input('id'));
        $result = $result->first();
        return $this->success($result);
    }
    
    public function get_with_comment(Request $request) {

        $validator = Validator::make($request->all(), [
            'id' => 'integer|min:0',
            'resource_id' => 'integer|min:0',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('discuss_theme')
            ->select([
                'discuss_theme.id as id',
                'discuss_theme.content as content',
                'discuss_theme.sort as sort',
                'discuss_theme.title as title',
            ]);
        if ($request->has('id'))
            $result = $result->where('discuss_theme.id', '=', $request->input('id'));
        if ($request->has('resource_id'))
            $result = $result->where('discuss_theme.resource_id', '=', $request->input('resource_id'));
        $result = $result->first();
        return $this->success($result);
    }
    public function edit(Request $request) {

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|min:0',
            'content' => 'string|max:100',
            'sort' => 'integer',
            'title' => 'string|max:100',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('discuss_theme');
        $info = 'discuss_themeController->edit: ';
        if ($request->has('id'))
            $result = $result->where('discuss_theme.id', '=', $request->input('id'));
        $info = $info . 'with: {'; 
        if ($request->has('id')) 
            $info = $info . 'id => ' . $request->input('id') . ', '; 
        $info = $info . 'id => ' . $request->input('id') . ', ';
        $info = $info . "}, ";
        $data =[];
        $info = $info . 'data: {';
        if ($request->has('content')){
            $data["content"] = $request->input('content');
            $info = $info . 'content => ' . $request->input('content') . ', ';
        }
        if ($request->has('sort')){
            $data["sort"] = $request->input('sort');
            $info = $info . 'sort => ' . $request->input('sort') . ', ';
        }
        if ($request->has('title')){
            $data["title"] = $request->input('title');
            $info = $info . 'title => ' . $request->input('title') . ', ';
        }
        $data["updated_at"] = date('Y-m-d H:i:s');
        $info = $info . 'updated_at => ' . date('Y-m-d H:i:s') . ', ';
        $info = $info . "}";
        Log::info($info);
        $result->update($data);
        return $this->success();
    }
}
