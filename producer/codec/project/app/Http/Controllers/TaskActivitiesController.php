<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Validator;

class TaskActivitiesController extends Controller
{
    public function delete(Request $request) {

        $validator = Validator::make($request->all(), [
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('task_activities');
        if ($request->has('id'))
            $result = $result->where('task_activities.id', '=', $request->input('id'));
        $result->delete();
        return $this->success();
    }
    public function update(Request $request) {

        $validator = Validator::make($request->all(), [
            'task_id' => 'integer|min:0',
            'activity_id' => 'integer|min:0',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('task_activities');
        $info = 'task_activitiesController->update: ';
        if ($request->has('task_id'))
            $result = $result->where('task_activities.task_id', '=', $request->input('task_id'));
        $info = $info . 'with: {'; 
        if ($request->has('id')) 
            $info = $info . 'id => ' . $request->input('id') . ', '; 
        $info = $info . 'task_id => ' . $request->input('task_id') . ', ';
        $info = $info . "}, ";
        $data =[];
        $info = $info . 'data: {';
        if ($request->has('activity_id')){
            $data["activity_id"] = $request->input('activity_id');
            $info = $info . 'activity_id => ' . $request->input('activity_id') . ', ';
        }
        $info = $info . "}";
        Log::info($info);
        $result->update($data);
        return $this->success();
    }
    public function create(Request $request) {

        $validator = Validator::make($request->all(), [
            'task_id' => 'required|integer|min:0',
            'activity_arrs' => 'required',
        ]);
        if($validator->fails()) {
            return $this->fails( $validator->errors()->all());
        }
        DB::table('task_activities')->where('task_id', '=', $request->input('task_id'))->delete();
        try{
            $activity_arrs = json_decode($request->input('activity_arrs'));
            foreach($activity_arrs as $activity){
                DB::table('task_activities')->insert([
                    'task_id' => $request->input('task_id'),
                    'activity_id' => $activity->activity_id,
                    'sort' => $activity->sort
                    ]);
            }
        }catch(\Exception $e){
            return $this->fails('activity_arrs不正确');
        }
        return $this->success();
    }
    public function create_one(Request $request) {

        $validator = Validator::make($request->all(), [
            'task_id' => 'integer|min:0',
            'activity_id' => 'integer|min:0',
            'sort' => 'integer',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        DB::table('task_activities')->insert([
            'task_id' => $request->input('task_id'),
            'activity_id' => $request->input('activity_id'),
            'sort' => $request->input('sort'),
        ]);
        return $this->success();
    }
    
    public function search(Request $request) {

        $validator = Validator::make($request->all(), [
            'offset' => 'required|integer|min:0',
            'length' => 'required|integer|min:1',
            'id' => 'integer|min:0',
            'task_id' => 'integer|min:0',
            'activity_id' => 'integer|min:0',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('task_activities')
            ->select([
                'task_activities.id as id',
                'task_activities.sort as sort',
                'activities_activity_id.id as activitie_id',
                'activities_activity_id.name as activitie_name',
                'activities_activity_id.resources as resources',
                'tasks_task_id.id as task_id',
                'tasks_task_id.name as task_name',
            ]);
        $result = $result->leftJoin('activities as activities_activity_id', 'activities_activity_id.id', '=', 'task_activities.activity_id');
        $result = $result->leftJoin('tasks as tasks_task_id', 'tasks_task_id.id', '=', 'task_activities.task_id');
        if ($request->has('id'))
            $result = $result->where('task_activities.id', '=', $request->input('id'));
        if ($request->has('task_id'))
            $result = $result->where('task_activities.task_id', '=', $request->input('task_id'));
        if ($request->has('activity_id'))
            $result = $result->where('task_activities.activity_id', '=', $request->input('activity_id'));
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
