<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Validator;

class ProjectTasksController extends Controller
{
    
    public function search_group_by_task(Request $request) {

        $validator = Validator::make($request->all(), [
            'offset' => 'required|integer|min:0',
            'length' => 'required|integer|min:1',
            'project_id' => 'integer|min:0',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('project_tasks')
            ->select([
                'project_tasks.sort as sort',
                'tasks_task_id.id as task_id',
                'tasks_task_id.name as task_name',
                'tasks_task_id.desc as task_desc',
                'tasks_task_id.start_date_time as start_date_time',
                'tasks_task_id.end_date_time as end_date_time',
            ]);
        $result = $result->leftJoin('tasks as tasks_task_id', 'tasks_task_id.id', '=', 'project_tasks.task_id');
        if ($request->has('project_id'))
            $result = $result->where('project_tasks.project_id', '=', $request->input('project_id'));
        $result = $result->orderBy('project_tasks.sort', 'asc');
        $count = $result->count();
        $result = $result
            ->offset($request->input('offset'))
            ->limit($request->input('length'))
            ->get();
        
        for($result_i = 0; $result_i < count($result); $result_i++) {
            $task_activities = DB::table('task_activities')->select([                'activities_activity_id.desc as activitie_desc',
                'activities_activity_id.name as activitie_name',
                'activities_activity_id.resources as resources',
                'icon_icon_type_id.file_name as file_name',
                'icon_icon_type_id.name as name',
'task_activities.sort as activite_sort',
            ]);
        $task_activities = $task_activities->leftJoin('activities as activities_activity_id', 'activities_activity_id.id', '=', 'task_activities.activity_id');
        $task_activities = $task_activities->leftJoin('icon_type as icon_type_icon_type_id', 'icon_type_icon_type_id.id', '=', 'activities_activity_id.icon_type_id');
        $task_activities = $task_activities->leftJoin('icon as icon_icon_type_id', 'icon_icon_type_id.icon_type_id', '=', 'icon_type_icon_type_id.id');
        $task_activities = $task_activities->where('task_id', $result[$result_i]->task_id);
        $task_activities = $task_activities->get();
        $result[$result_i]->task_activities = $task_activities;
        }
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
            'project_id' => 'integer|min:0',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('project_tasks')
            ->select([
                'project_tasks.id as id',
                'project_tasks.sort as sort',
                'tasks_task_id.id as task_id',
                'tasks_task_id.name as task_name',
                'activities_activity_id.resources as resources',
            ]);
        $result = $result->leftJoin('tasks as tasks_task_id', 'tasks_task_id.id', '=', 'project_tasks.task_id');
        $result = $result->leftJoin('task_activities as task_activities_task_id', 'task_activities_task_id.task_id', '=', 'tasks_task_id.id');
        $result = $result->leftJoin('activities as activities_activity_id', 'activities_activity_id.id', '=', 'task_activities_task_id.activity_id');
        if ($request->has('project_id'))
            $result = $result->where('project_tasks.project_id', '=', $request->input('project_id'));
        $result = $result->orderBy('project_tasks.sort', 'asc');
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
    
    public function search_no_activity(Request $request) {

        $validator = Validator::make($request->all(), [
            'offset' => 'required|integer|min:0',
            'length' => 'required|integer|min:1',
            'project_id' => 'integer|min:0',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('project_tasks')
            ->select([
                'project_tasks.id as id',
                'project_tasks.sort as sort',
                'tasks_task_id.id as task_id',
                'tasks_task_id.name as task_name',
                'tasks_task_id.desc as task_desc',
                'tasks_task_id.start_date_time as task_start_study_time',
                'tasks_task_id.end_date_time as task_end_study_time',
            ]);
        $result = $result->leftJoin('tasks as tasks_task_id', 'tasks_task_id.id', '=', 'project_tasks.task_id');
        if ($request->has('project_id'))
            $result = $result->where('project_tasks.project_id', '=', $request->input('project_id'));
        $result = $result->orderBy('project_tasks.sort', 'asc');
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
    public function create_product_task(Request $request) {

        $validator = Validator::make($request->all(), [
            'product_id' => 'integer|min:0',
            'task_id' => 'integer|min:0',
            'sort' => 'integer|min:0',
        ]);
        if($validator->fails()) {
            return $this->fails( $validator->errors()->all());
        }
        $product = DB::table('products')->select('project_id')->where('id', '=', $request->input('product_id'))->first();
        if($product == null){
            return $this->fails("不存在此产品");
        }
        DB::table('project_tasks')->insert([
            'project_id' => $product->project_id,
            'task_id' => $request->input('task_id'),
            'sort' => $request->input('sort'),
        ]);
        return $this->success();
    }
    public function create(Request $request) {
        DB::beginTransaction();
        $task_id = DB::table("tasks")->insertGetId([
            "name" => $request->input("task_name"),
            "desc" => $request->input("task_desc"),
            "start_date_time" => $request->input("task_start_study_time"),
            "end_date_time" => $request->input("task_end_study_time"),
            "created_at" => date("Y-m-d H:i:s"),
            "updated_at" => date("Y-m-d H:i:s"),
          ]);
        $project_tasks_id = DB::table("project_tasks")->insertGetId([
            "project_id" => $request->input("project_id"),
            "task_id" => $task_id,
            "sort" => $request->input("project_tasks_sort"),
          ]);
        
        DB::commit();
        return $this->success();
    }
    public function delete(Request $request) {

        $validator = Validator::make($request->all(), [
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('project_tasks');
        if ($request->has('id'))
            $result = $result->where('project_tasks.id', '=', $request->input('id'));
        $result->delete();
        return $this->success();
    }
    public function edit(Request $request) {

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|min:0',
            'task_id' => 'integer|min:0',
            'sort' => 'integer|min:0',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('project_tasks');
        $info = 'project_tasksController->edit: ';
        if ($request->has('id'))
            $result = $result->where('project_tasks.id', '=', $request->input('id'));
        $info = $info . 'with: {'; 
        if ($request->has('id')) 
            $info = $info . 'id => ' . $request->input('id') . ', '; 
        $info = $info . 'id => ' . $request->input('id') . ', ';
        $info = $info . "}, ";
        $data =[];
        $info = $info . 'data: {';
        if ($request->has('task_id')){
            $data["task_id"] = $request->input('task_id');
            $info = $info . 'task_id => ' . $request->input('task_id') . ', ';
        }
        if ($request->has('sort')){
            $data["sort"] = $request->input('sort');
            $info = $info . 'sort => ' . $request->input('sort') . ', ';
        }
        $info = $info . "}";
        Log::info($info);
        $result->update($data);
        return $this->success();
    }
}
