<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Validator;

class ClassController extends Controller
{
    public function search_count(Request $request){
        
        $validator = Validator::make($request->all(), [
            'offset' => 'required|integer|min:0',
            'length' => 'required|integer|min:1',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('class')
            ->select([
                'class.id as id',
                'class_users_class_id.user_id as user_id',
            ]);
        $result = $result->leftJoin('class_users as class_users_class_id', 'class_users_class_id.class_id', '=', 'class.id');
        $result = $result
            ->offset($request->input('offset'))
            ->limit($request->input('length'))
            ->get();
        $result_group = array();
        foreach ($result as $item){
            if (!array_key_exists($item->id, $result_group)){
                $result_group[$item->id] = array();
                array_push($result_group[$item->id], $item);
            } else {
                array_push($result_group[$item->id], $item);
            }
        }
        
        $result = [
            'data' => $result_group,
            'total' => count($result_group)
        ];
        return $this->success($result);
    }
    public function create(Request $request) {

        $validator = Validator::make($request->all(), [
            'name' => 'string|max:100',
            'product_id' => 'integer|min:0',
            'task_id' => 'integer|min:0',
            'activitie_id' => 'integer|min:0',
            'start_time' => 'string|max:100',
            'instructor_id' => 'integer|min:0',
            'state' => 'integer',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        DB::table('class')->insert([
            'name' => $request->input('name'),
            'shop_id' => $this->token->shop_id,
            'product_id' => $request->input('product_id'),
            'task_id' => $request->input('task_id'),
            'activitie_id' => $request->input('activitie_id'),
            'start_time' => $request->input('start_time'),
            'instructor_id' => $request->input('instructor_id'),
            'state' => $request->input('state'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        return $this->success();
    }
    public function edit(Request $request) {

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|min:0',
            'name' => 'string|max:100',
            'product_id' => 'integer|min:0',
            'task_id' => 'integer|min:0',
            'start_time' => 'string|max:100',
            'activitie_id' => 'integer|min:0',
            'instructor_id' => 'integer|min:0',
            'state' => 'integer',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('class');
        $info = 'classController->edit: ';
        if ($request->has('id'))
            $result = $result->where('class.id', '=', $request->input('id'));
        $info = $info . 'with: {'; 
        if ($request->has('id')) 
            $info = $info . 'id => ' . $request->input('id') . ', '; 
        $info = $info . 'id => ' . $request->input('id') . ', ';
        $info = $info . "}, ";
        $data =[];
        $info = $info . 'data: {';
        if ($request->has('name')){
            $data["name"] = $request->input('name');
            $info = $info . 'name => ' . $request->input('name') . ', ';
        }
        if ($request->has('shop_id')){
            $data["shop_id"] = $this->token->shop_id;
            $info = $info . 'shop_id => ' . $this->token->shop_id . ', ';
        }
        if ($request->has('product_id')){
            $data["product_id"] = $request->input('product_id');
            $info = $info . 'product_id => ' . $request->input('product_id') . ', ';
        }
        if ($request->has('task_id')){
            $data["task_id"] = $request->input('task_id');
            $info = $info . 'task_id => ' . $request->input('task_id') . ', ';
        }
        if ($request->has('start_time')){
            $data["start_time"] = $request->input('start_time');
            $info = $info . 'start_time => ' . $request->input('start_time') . ', ';
        }
        if ($request->has('activitie_id')){
            $data["activitie_id"] = $request->input('activitie_id');
            $info = $info . 'activitie_id => ' . $request->input('activitie_id') . ', ';
        }
        if ($request->has('instructor_id')){
            $data["instructor_id"] = $request->input('instructor_id');
            $info = $info . 'instructor_id => ' . $request->input('instructor_id') . ', ';
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
    
    public function search_shop(Request $request) {

        $validator = Validator::make($request->all(), [
            'offset' => 'required|integer|min:0',
            'length' => 'required|integer|min:1',
            'name' => 'string|max:100',
            'state' => 'integer',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('class')
            ->select([
                'class.id as id',
                'class.name as name',
                'shops_shop_id.name as shop_name',
                'products_product_id.id as product_id',
                'products_product_id.name as product_name',
                'instructor_instructor_id.id as instructor_id',
                'instructor_instructor_id.name as instructor_name',
                'class.start_time as start_time',
                'tasks_task_id.name as task_name',
                'tasks_task_id.desc as task_desc',
                'class.activitie_id as activitie_id',
                'class.state as state',
            ]);
        $result = $result->leftJoin('shops as shops_shop_id', 'shops_shop_id.id', '=', 'class.shop_id');
        $result = $result->leftJoin('products as products_product_id', 'products_product_id.id', '=', 'class.product_id');
        $result = $result->leftJoin('instructor as instructor_instructor_id', 'instructor_instructor_id.id', '=', 'class.instructor_id');
        $result = $result->leftJoin('tasks as tasks_task_id', 'tasks_task_id.id', '=', 'class.task_id');
        $result = $result->where('class.shop_id', '=', $this->token->shop_id);
        if ($request->has('name'))
            $result = $result->where('class.name', 'like', '%'.$request->input('name').'%');
        if ($request->has('state'))
            $result = $result->where('class.state', '=', $request->input('state'));
        $result = $result->orderBy('class.id', 'desc');
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
    
    public function search(Request $request) {

        $validator = Validator::make($request->all(), [
            'offset' => 'required|integer|min:0',
            'length' => 'required|integer|min:1',
            'name' => 'string|max:100',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('class')
            ->select([
                'class.id as id',
                'class.name as name',
                'shops_shop_id.name as shop_name',
                'products_product_id.id as product_id',
                'products_product_id.name as product_name',
                'instructor_instructor_id.id as instructor_id',
                'instructor_instructor_id.name as instructor_name',
                'class.start_time as start_time',
                'tasks_task_id.name as task_name',
                'tasks_task_id.desc as task_desc',
                'class.activitie_id as activitie_id',
                'class.state as state',
            ]);
        $result = $result->leftJoin('shops as shops_shop_id', 'shops_shop_id.id', '=', 'class.shop_id');
        $result = $result->leftJoin('products as products_product_id', 'products_product_id.id', '=', 'class.product_id');
        $result = $result->leftJoin('instructor as instructor_instructor_id', 'instructor_instructor_id.id', '=', 'class.instructor_id');
        $result = $result->leftJoin('tasks as tasks_task_id', 'tasks_task_id.id', '=', 'class.task_id');
        $result = $result->where('class.shop_id', '=', $this->token->shop_id);
        if ($request->has('name'))
            $result = $result->where('class.name', 'like', '%'.$request->input('name').'%');
        $result = $result->orderBy('class.id', 'desc');
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
    
    public function instructor_search(Request $request) {

        $validator = Validator::make($request->all(), [
            'offset' => 'required|integer|min:0',
            'length' => 'required|integer|min:1',
            'name' => 'string|max:100',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('class')
            ->select([
                'class.id as id',
                'class.name as name',
                'shops_shop_id.name as shop_name',
                'products_product_id.id as product_id',
                'products_product_id.period as product_period',
                'projects_project_id.id as project_id',
                'projects_project_id.name as project_name',
                'projects_project_id.start_time as project_start_time',
                'projects_project_id.end_study_time as project_end_study_time',
                'projects_project_id.start_stydy_time as project_start_stydy_time',
                'projects_project_id.organizer as project_organizer',
                'projects_project_id.end_time as project_end_time',
                'projects_project_id.onsale as project_onsale',
                'projects_project_id.sponsor as project_sponsor',
                'instructor_instructor_id.id as instructor_id',
                'instructor_instructor_id.name as instructor_name',
                'tasks_task_id.name as task_name',
                'tasks_task_id.desc as task_desc',
                'class.activitie_id as activitie_id',
                'class.state as state',
            ]);
        $result = $result->leftJoin('shops as shops_shop_id', 'shops_shop_id.id', '=', 'class.shop_id');
        $result = $result->leftJoin('products as products_product_id', 'products_product_id.id', '=', 'class.product_id');
        $result = $result->leftJoin('projects as projects_project_id', 'projects_project_id.id', '=', 'products_product_id.project_id');
        $result = $result->leftJoin('instructor as instructor_instructor_id', 'instructor_instructor_id.id', '=', 'class.instructor_id');
        $result = $result->leftJoin('tasks as tasks_task_id', 'tasks_task_id.id', '=', 'class.task_id');
        $result = $result->where('class.instructor_id', '=', $this->token->id);
        if ($request->has('name'))
            $result = $result->where('class.name', 'like', '%'.$request->input('name').'%');
        $result = $result->orderBy('class.id', 'desc');
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

        $result = DB::table('class')
            ->select([
                'class.id as id',
                'class.name as name',
                'shops_shop_id.name as shop_name',
                'products_product_id.id as product_id',
                'products_product_id.name as product_name',
                'instructor_instructor_id.id as instructor_id',
                'instructor_instructor_id.name as instructor_name',
                'class.start_time as start_time',
                'tasks_task_id.name as task_name',
                'tasks_task_id.desc as task_desc',
                'class.activitie_id as activitie_id',
                'class.state as state',
            ]);
        $result = $result->leftJoin('shops as shops_shop_id', 'shops_shop_id.id', '=', 'class.shop_id');
        $result = $result->leftJoin('products as products_product_id', 'products_product_id.id', '=', 'class.product_id');
        $result = $result->leftJoin('instructor as instructor_instructor_id', 'instructor_instructor_id.id', '=', 'class.instructor_id');
        $result = $result->leftJoin('tasks as tasks_task_id', 'tasks_task_id.id', '=', 'class.task_id');
        if ($request->has('id'))
            $result = $result->where('class.id', '=', $request->input('id'));
        $result = $result->first();
        return $this->success($result);
    }
    public function delete(Request $request) {

        $validator = Validator::make($request->all(), [
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('class');
        if ($request->has('id'))
            $result = $result->where('class.id', '=', $request->input('id'));
        $result->delete();
        return $this->success();
    }
    
    public function get_rate_by_user_id_and_class_id(Request $request) {

        $validator = Validator::make($request->all(), [
            'offset' => 'required|integer|min:0',
            'length' => 'required|integer|min:1',
            'id' => 'integer|min:0',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('class')
            ->select([
                'purchased_order_id.rate as rate',
            ]);
        $result = $result->leftJoin('class_users as class_users_class_id', 'class_users_class_id.class_id', '=', 'class.id');
        $result = $result->leftJoin('users as users_user_id', 'users_user_id.id', '=', 'class_users_class_id.user_id');
        $result = $result->leftJoin('orders as orders_owner_id', 'orders_owner_id.owner_id', '=', 'users_user_id.id');
        $result = $result->leftJoin('purchased as purchased_order_id', 'purchased_order_id.order_id', '=', 'orders_owner_id.id');
        if ($request->has('user_id'))
            $result = $result->where('orders_owner_id.owner_id', '=', $request->input('user_id'));
        if ($request->has('id'))
            $result = $result->where('class.id', '=', $request->input('id'));
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
    public function autoCreate(Request $request) 
    {
        $result = \App\Utils\ClassUtil::autoCreate($request);
        return $this->success($result);
    }
}
