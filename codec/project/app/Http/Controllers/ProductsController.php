<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Validator;

class ProductsController extends Controller
{
    public function update_activity(Request $request) 
    {
        $result = \App\Utils\ProductsUtil::update_activity($request);
        return $this->success($result);
    }
    
    public function get_activities_by_product_id(Request $request) {

        $validator = Validator::make($request->all(), [
            'id' => 'integer|min:0',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('products')
            ->select([
                'tasks_task_id.id as task_id',
            ]);
        $result = $result->leftJoin('projects as projects_project_id', 'projects_project_id.id', '=', 'products.project_id');
        $result = $result->leftJoin('project_tasks as project_tasks_project_id', 'project_tasks_project_id.project_id', '=', 'projects_project_id.id');
        $result = $result->leftJoin('tasks as tasks_task_id', 'tasks_task_id.id', '=', 'project_tasks_project_id.task_id');
        if ($request->has('id'))
            $result = $result->where('products.id', '=', $request->input('id'));
        $result = $result->first();
            $item = DB::table('task_activities')->select([                'activities_activity_id.id as activity_id',
                'activities_activity_id.name as activitie_name',
                'activities_activity_id.resources as resources',
            ]);
        $item = $item->leftJoin('activities as activities_activity_id', 'activities_activity_id.id', '=', 'task_activities.activity_id');
        $item = $item->where('task_id', $result->task_id);
        $result->task_activities = $item->get();
        return $this->success($result);
    }
    public function set_many_onsale(Request $request) {

        $validator = Validator::make($request->all(), [
            'onsale' => 'boolean',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('products');
        $info = 'productsController->set_many_onsale: ';
        if ($request->has('id'))
            $result = $result->where('products.id', $request->input('id'));
        $info = $info . 'with: {'; 
        if ($request->has('id')) 
            $info = $info . 'id => ' . $request->input('id') . ', '; 
        if ($request->has('id_arr')){
            $result = $result->whereIn('id', json_decode($request->input('id_arr')));
            $info = $info . "id =>" . $request->input('id_arr');
        }
        $info = $info . "}, ";
        $data =[];
        $info = $info . 'data: {';
        if ($request->has('onsale')){
            $data["onsale"] = $request->input('onsale');
            $info = $info . 'onsale => ' . $request->input('onsale') . ', ';
        }
        $data["updated_at"] = date('Y-m-d H:i:s');
        $info = $info . 'updated_at => ' . date('Y-m-d H:i:s') . ', ';
        $info = $info . "}";
        Log::info($info);
        $result->update($data);
        return $this->success();
    }
    public function copyProduct(Request $request) 
    {
        $result = \App\Utils\ProductsUtil::copyProduct($request);
        return $this->success($result);
    }
    public function get_banner_img(Request $request) 
    {
        $result = \App\Utils\ProductsUtil::get_banner_img($request);
        return $this->success($result);
    }
    
    public function get_task_by_product_id(Request $request) {

        $validator = Validator::make($request->all(), [
            'offset' => 'required|integer|min:0',
            'length' => 'required|integer|min:1',
            'id' => 'integer|min:0',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('products')
            ->select([
                'project_tasks_project_id.project_id as project_id',
                'tasks_task_id.id as task_id',
                'tasks_task_id.name as task_name',
                'tasks_task_id.desc as task_desc',
                'activities_activity_id.id as activitie_id',
                'activities_activity_id.name as activitie_name',
                'activities_activity_id.desc as activitie_desc',
            ]);
        $result = $result->leftJoin('projects as projects_project_id', 'projects_project_id.id', '=', 'products.project_id');
        $result = $result->leftJoin('project_tasks as project_tasks_project_id', 'project_tasks_project_id.project_id', '=', 'projects_project_id.id');
        $result = $result->leftJoin('tasks as tasks_task_id', 'tasks_task_id.id', '=', 'project_tasks_project_id.task_id');
        $result = $result->leftJoin('task_activities as task_activities_task_id', 'task_activities_task_id.task_id', '=', 'tasks_task_id.id');
        $result = $result->leftJoin('activities as activities_activity_id', 'activities_activity_id.id', '=', 'task_activities_task_id.activity_id');
        if ($request->has('id'))
            $result = $result->where('products.id', '=', $request->input('id'));
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
            'onsale' => 'boolean',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('products')
            ->select([
                'products.id as id',
                'products.name as name',
                'products.desc as desc',
                'products.image as image',
                'products.price as price',
                'products.period as period',
                'products.project_id as project_id',
                'products.teacher as teacher',
                'products.teacher_intro as teacher_intro',
                'products.onsale as onsale',
                'products.is_auth as is_auth',
                'products.attr as attr',
                'products.cer_year as cer_year',
                'products.cer_industry as cer_industry',
                'products.is_project as is_project',
                'shops_shop_id.name as shop_name',
            ]);
        $result = $result->leftJoin('shops as shops_shop_id', 'shops_shop_id.id', '=', 'products.shop_id');
        if ($request->has('id'))
            $result = $result->where('products.id', '=', $request->input('id'));
        if ($request->has('onsale'))
            $result = $result->where('products.onsale', '=', $request->input('onsale'));
        $result = $result->first();
        return $this->success($result);
    }
    
    public function get_pay(Request $request) {

        $validator = Validator::make($request->all(), [
            'id' => 'integer|min:0',
            'onsale' => 'boolean',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('products')
            ->select([
                'products.id as id',
                'products.name as name',
                'products.desc as desc',
                'products.image as image',
                'products.price as price',
                'products.period as period',
                'products.project_id as project_id',
                'products.teacher as teacher',
                'products.teacher_intro as teacher_intro',
                'products.onsale as onsale',
                'products.is_auth as is_auth',
                'products.attr as attr',
                'products.cer_year as cer_year',
                'products.cer_industry as cer_industry',
                'shops_shop_id.name as shop_name',
                'purchased_product_id.id as purchased_id',
                'orders_order_id.owner_id as owner_id',
            ]);
        $result = $result->leftJoin('shops as shops_shop_id', 'shops_shop_id.id', '=', 'products.shop_id');
        $result = $result->leftJoin('purchased as purchased_product_id', 'purchased_product_id.product_id', '=', 'products.id');
        $result = $result->leftJoin('orders as orders_order_id', 'orders_order_id.id', '=', 'purchased_product_id.order_id');
        if ($request->has('id'))
            $result = $result->where('products.id', '=', $request->input('id'));
        $result = $result->where('orders_order_id.owner_id', '=', $this->token->id);
        if ($request->has('onsale'))
            $result = $result->where('products.onsale', '=', $request->input('onsale'));
        $result = $result->first();
        return $this->success($result);
    }
    
    public function search(Request $request) {

        $validator = Validator::make($request->all(), [
            'offset' => 'required|integer|min:0',
            'length' => 'required|integer|min:1',
            'shop_id' => 'integer',
            'name' => 'string|max:100',
            'onsale' => 'boolean',
            'is_auth' => 'boolean',
            'cer_year' => 'string|max:100',
            'attr' => 'string|max:100',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('products')
            ->select([
                'products.id as id',
                'products.name as name',
                'products.desc as desc',
                'products.image as image',
                'products.price as price',
                'products.period as period',
                'products.project_id as project_id',
                'products.teacher as teacher',
                'products.teacher_intro as teacher_intro',
                'products.onsale as onsale',
                'products.is_auth as is_auth',
                'products.attr as attr',
                'products.cer_year as cer_year',
                'products.cer_industry as cer_industry',
                'products.is_project as is_project',
                'shops_shop_id.name as shop_name',
            ]);
        $result = $result->leftJoin('shops as shops_shop_id', 'shops_shop_id.id', '=', 'products.shop_id');
        if ($request->has('shop_id'))
            $result = $result->where('products.shop_id', '=', $request->input('shop_id'));
        if ($request->has('name'))
            $result = $result->where('products.name', 'like', '%'.$request->input('name').'%');
        if ($request->has('onsale'))
            $result = $result->where('products.onsale', '=', $request->input('onsale'));
        if ($request->has('is_auth'))
            $result = $result->where('products.is_auth', '=', $request->input('is_auth'));
        if ($request->has('cer_year'))
            $result = $result->where('products.cer_year', '=', $request->input('cer_year'));
        if ($request->has('attr'))
            $result = $result->where('products.attr', '=', $request->input('attr'));
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
    
    public function shop_search(Request $request) {

        $validator = Validator::make($request->all(), [
            'offset' => 'required|integer|min:0',
            'length' => 'required|integer|min:1',
            'is_project' => 'integer',
            'name' => 'string|max:100',
            'teacher' => 'string|max:100',
            'onsale' => 'boolean',
            'is_auth' => 'boolean',
            'cer_year' => 'string|max:100',
            'attr' => 'string|max:100',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('products')
            ->select([
                'products.id as id',
                'products.name as name',
                'products.desc as desc',
                'products.image as image',
                'products.price as price',
                'products.period as period',
                'products.project_id as project_id',
                'products.is_project as is_project',
                'products.teacher as teacher',
                'products.teacher_intro as teacher_intro',
                'products.onsale as onsale',
                'products.is_auth as is_auth',
                'products.attr as attr',
                'products.cer_year as cer_year',
                'products.cer_industry as cer_industry',
                'products.created_at as created_at',
                'shops_shop_id.name as shop_name',
                'labels_label_id.id as label_id',
                'labels_label_id.name as label_name',
            ]);
        $result = $result->leftJoin('shops as shops_shop_id', 'shops_shop_id.id', '=', 'products.shop_id');
        $result = $result->leftJoin('product_labels as product_labels_product_id', 'product_labels_product_id.product_id', '=', 'products.id');
        $result = $result->leftJoin('labels as labels_label_id', 'labels_label_id.id', '=', 'product_labels_product_id.label_id');
        $result = $result->where('products.shop_id', '=', $this->token->shop_id);
        if ($request->has('is_project'))
            $result = $result->where('products.is_project', '=', $request->input('is_project'));
        if ($request->has('name'))
            $result = $result->where('products.name', 'like', '%'.$request->input('name').'%');
        if ($request->has('teacher'))
            $result = $result->where('products.teacher', 'like', '%'.$request->input('teacher').'%');
        if ($request->has('onsale'))
            $result = $result->where('products.onsale', '=', $request->input('onsale'));
        if ($request->has('is_auth'))
            $result = $result->where('products.is_auth', '=', $request->input('is_auth'));
        if ($request->has('cer_year'))
            $result = $result->where('products.cer_year', '=', $request->input('cer_year'));
        if ($request->has('attr'))
            $result = $result->where('products.attr', '=', $request->input('attr'));
        if ($request->has('created_at'))
            $result = $result->where('products.created_at', 'like', '%'.$request->input('created_at').'%');
        if ($request->has('label_id'))
            $result = $result->where('labels_label_id.id', '=', $request->input('label_id'));
        $result = $result->groupBy('products.id');
        $count = count($result->get());
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
        DB::beginTransaction();
        $project_id = DB::table("projects")->insertGetId([
            "name" => $request->input("name"),
            "shop_id" => $this->token->shop_id,
            "desc" => $request->input("desc"),
            "created_at" => date("Y-m-d H:i:s"),
            "updated_at" => date("Y-m-d H:i:s"),
          ]);
        $product_id = DB::table("products")->insertGetId([
            "name" => $request->input("name"),
            "desc" => $request->input("desc"),
            "image" => $request->input("image"),
            "price" => $request->input("price"),
            "project_id" => $project_id,
            "period" => $request->input("period"),
            "teacher" => $request->input("teacher"),
            "teacher_intro" => $request->input("teacher_intro"),
            "onsale" => $request->input("onsale"),
            "is_auth" => $request->input("is_auth"),
            "attr" => $request->input("attr"),
            "cer_year" => $request->input("cer_year"),
            "cer_industry" => $request->input("cer_industry"),
            "shop_id" => $this->token->shop_id,
            "is_project" => "0",
            "created_at" => date("Y-m-d H:i:s"),
            "updated_at" => date("Y-m-d H:i:s"),
          ]);
        $product_labels_id = DB::table("product_labels")->insertGetId([
            "product_id" => $product_id,
            "label_id" => $request->input("label_id"),
          ]);
        $task_id = DB::table("tasks")->insertGetId([
            "name" => $request->input("name"),
            "shop_id" => $this->token->shop_id,
            "desc" => $request->input("desc"),
            "created_at" => date("Y-m-d H:i:s"),
            "updated_at" => date("Y-m-d H:i:s"),
          ]);
        $project_task_id = DB::table("project_tasks")->insertGetId([
            "project_id" => $project_id,
            "task_id" => $task_id,
          ]);
        $task_activities_id = DB::table("task_activities")->insertGetId([
            "task_id" => $task_id,
            "activity_id" => $request->input("activity_id"),
          ]);
        
        DB::commit();
        return $this->success();
    }
    public function create_product(Request $request) {
        DB::beginTransaction();
        $product_id = DB::table("products")->insertGetId([
            "name" => $request->input("name"),
            "desc" => $request->input("desc"),
            "image" => $request->input("image"),
            "price" => $request->input("price"),
            "project_id" => $request->input("project_id"),
            "period" => $request->input("period"),
            "teacher" => $request->input("teacher"),
            "teacher_intro" => $request->input("teacher_intro"),
            "onsale" => $request->input("onsale"),
            "is_auth" => $request->input("is_auth"),
            "attr" => $request->input("attr"),
            "cer_year" => $request->input("cer_year"),
            "cer_industry" => $request->input("cer_industry"),
            "shop_id" => $this->token->shop_id,
            "is_project" => "1",
            "created_at" => date("Y-m-d H:i:s"),
            "updated_at" => date("Y-m-d H:i:s"),
          ]);
        $product_label_id = DB::table("product_labels")->insertGetId([
            "product_id" => $product_id,
            "label_id" => $request->input("label_id"),
          ]);
        
        DB::commit();
        return $this->success();
    }
    public function edit(Request $request) {

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|min:0',
            'name' => 'required|string|max:100',
            'desc' => 'string',
            'image' => 'string|max:200',
            'price' => 'numeric',
            'project_id' => 'integer|min:0',
            'period' => 'string|max:100',
            'teacher' => 'string|max:100',
            'teacher_intro' => 'string',
            'onsale' => 'boolean',
            'is_auth' => 'boolean',
            'attr' => 'string|max:100',
            'cer_year' => 'string|max:100',
            'cer_industry' => 'string|max:100',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('products');
        $info = 'productsController->edit: ';
        if ($request->has('id'))
            $result = $result->where('products.id', '=', $request->input('id'));
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
        if ($request->has('desc')){
            $data["desc"] = $request->input('desc');
            $info = $info . 'desc => ' . $request->input('desc') . ', ';
        }
        if ($request->has('image')){
            $data["image"] = $request->input('image');
            $info = $info . 'image => ' . $request->input('image') . ', ';
        }
        if ($request->has('price')){
            $data["price"] = $request->input('price');
            $info = $info . 'price => ' . $request->input('price') . ', ';
        }
        if ($request->has('project_id')){
            $data["project_id"] = $request->input('project_id');
            $info = $info . 'project_id => ' . $request->input('project_id') . ', ';
        }
        if ($request->has('period')){
            $data["period"] = $request->input('period');
            $info = $info . 'period => ' . $request->input('period') . ', ';
        }
        if ($request->has('teacher')){
            $data["teacher"] = $request->input('teacher');
            $info = $info . 'teacher => ' . $request->input('teacher') . ', ';
        }
        if ($request->has('teacher_intro')){
            $data["teacher_intro"] = $request->input('teacher_intro');
            $info = $info . 'teacher_intro => ' . $request->input('teacher_intro') . ', ';
        }
        if ($request->has('onsale')){
            $data["onsale"] = $request->input('onsale');
            $info = $info . 'onsale => ' . $request->input('onsale') . ', ';
        }
        if ($request->has('is_auth')){
            $data["is_auth"] = $request->input('is_auth');
            $info = $info . 'is_auth => ' . $request->input('is_auth') . ', ';
        }
        if ($request->has('attr')){
            $data["attr"] = $request->input('attr');
            $info = $info . 'attr => ' . $request->input('attr') . ', ';
        }
        if ($request->has('cer_year')){
            $data["cer_year"] = $request->input('cer_year');
            $info = $info . 'cer_year => ' . $request->input('cer_year') . ', ';
        }
        if ($request->has('cer_industry')){
            $data["cer_industry"] = $request->input('cer_industry');
            $info = $info . 'cer_industry => ' . $request->input('cer_industry') . ', ';
        }
        $data["updated_at"] = date('Y-m-d H:i:s');
        $info = $info . 'updated_at => ' . date('Y-m-d H:i:s') . ', ';
        $info = $info . "}";
        Log::info($info);
        $result->update($data);
        return $this->success();
    }
    
    public function shop_get(Request $request) {

        $validator = Validator::make($request->all(), [
            'id' => 'integer|min:0',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('products')
            ->select([
                'products.name as name',
                'products.desc as desc',
                'products.image as image',
                'products.price as price',
                'products.period as period',
                'products.teacher as teacher',
                'products.teacher_intro as teacher_intro',
                'products.onsale as onsale',
                'products.is_auth as is_auth',
                'products.attr as attr',
                'products.cer_year as cer_year',
                'products.cer_industry as cer_industry',
                'shops_shop_id.name as shop_name',
            ]);
        $result = $result->leftJoin('shops as shops_shop_id', 'shops_shop_id.id', '=', 'products.shop_id');
        if ($request->has('id'))
            $result = $result->where('products.id', '=', $request->input('id'));
        $result = $result->first();
        return $this->success($result);
    }
    public function shop_delete(Request $request) {

        $validator = Validator::make($request->all(), [
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('products');
        if ($request->has('id'))
            $result = $result->where('products.id', '=', $request->input('id'));
        $result->delete();
        return $this->success();
    }
}
