<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Validator;

class ProjectsController extends Controller
{
    
    public function search(Request $request) {

        $validator = Validator::make($request->all(), [
            'id' => 'integer|min:0',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('projects')
            ->select([
                'projects.id as id',
                'projects.name as name',
                'projects.start_time as start_time',
                'projects.end_time as end_time',
                'projects.onsale as onsale',
                'projects.desc as desc',
                'projects.sponsor as sponsor',
                'labels_label_id.name as label_name',
                'projects.organizer as organizer',
                'projects.period as period',
                'projects.start_stydy_time as start_stydy_time',
                'projects.end_study_time as end_study_time',
                'project_tasks_project_id.project_id as project_id',
                'activities_activity_id.resources as resources',
            ]);
        $result = $result->leftJoin('labels as labels_label_id', 'labels_label_id.id', '=', 'projects.label_id');
        $result = $result->leftJoin('project_tasks as project_tasks_project_id', 'project_tasks_project_id.project_id', '=', 'projects.id');
        $result = $result->leftJoin('tasks as tasks_task_id', 'tasks_task_id.id', '=', 'project_tasks_project_id.task_id');
        $result = $result->leftJoin('task_activities as task_activities_task_id', 'task_activities_task_id.task_id', '=', 'tasks_task_id.id');
        $result = $result->leftJoin('activities as activities_activity_id', 'activities_activity_id.id', '=', 'task_activities_task_id.activity_id');
        if ($request->has('id'))
            $result = $result->where('projects.id', '=', $request->input('id'));
        $result = $result->first();
        return $this->success($result);
    }
    public function create(Request $request) {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'desc' => 'required|string',
            'start_time' => 'date',
            'end_time' => 'date',
            'onsale' => 'integer',
            'label_id' => 'integer|min:0',
            'sponsor' => 'string|max:100',
            'organizer' => 'string|max:100',
            'period' => 'integer',
            'start_stydy_time' => 'date',
            'end_study_time' => 'date',
            'prportion' => 'integer',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        DB::table('projects')->insert([
            'shop_id' => $this->token->shop_id,
            'name' => $request->input('name'),
            'desc' => $request->input('desc'),
            'start_time' => $request->input('start_time'),
            'end_time' => $request->input('end_time'),
            'onsale' => $request->input('onsale'),
            'label_id' => $request->input('label_id'),
            'sponsor' => $request->input('sponsor'),
            'organizer' => $request->input('organizer'),
            'period' => $request->input('period'),
            'start_stydy_time' => $request->input('start_stydy_time'),
            'end_study_time' => $request->input('end_study_time'),
            'prportion' => $request->input('prportion'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        return $this->success();
    }
    public function edit(Request $request) {

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|min:0',
            'name' => 'required|string|max:100',
            'desc' => 'required|string',
            'start_time' => 'date',
            'end_time' => 'date',
            'onsale' => 'integer',
            'sponsor' => 'string|max:100',
            'label_id' => 'integer|min:0',
            'organizer' => 'string|max:100',
            'period' => 'integer',
            'start_stydy_time' => 'date',
            'end_study_time' => 'date',
            'prportion' => 'integer',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('projects');
        $info = 'projectsController->edit: ';
        if ($request->has('id'))
            $result = $result->where('projects.id', '=', $request->input('id'));
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
        if ($request->has('start_time')){
            $data["start_time"] = $request->input('start_time');
            $info = $info . 'start_time => ' . $request->input('start_time') . ', ';
        }
        if ($request->has('end_time')){
            $data["end_time"] = $request->input('end_time');
            $info = $info . 'end_time => ' . $request->input('end_time') . ', ';
        }
        if ($request->has('onsale')){
            $data["onsale"] = $request->input('onsale');
            $info = $info . 'onsale => ' . $request->input('onsale') . ', ';
        }
        if ($request->has('sponsor')){
            $data["sponsor"] = $request->input('sponsor');
            $info = $info . 'sponsor => ' . $request->input('sponsor') . ', ';
        }
        if ($request->has('label_id')){
            $data["label_id"] = $request->input('label_id');
            $info = $info . 'label_id => ' . $request->input('label_id') . ', ';
        }
        if ($request->has('organizer')){
            $data["organizer"] = $request->input('organizer');
            $info = $info . 'organizer => ' . $request->input('organizer') . ', ';
        }
        if ($request->has('period')){
            $data["period"] = $request->input('period');
            $info = $info . 'period => ' . $request->input('period') . ', ';
        }
        if ($request->has('start_stydy_time')){
            $data["start_stydy_time"] = $request->input('start_stydy_time');
            $info = $info . 'start_stydy_time => ' . $request->input('start_stydy_time') . ', ';
        }
        if ($request->has('end_study_time')){
            $data["end_study_time"] = $request->input('end_study_time');
            $info = $info . 'end_study_time => ' . $request->input('end_study_time') . ', ';
        }
        if ($request->has('prportion')){
            $data["prportion"] = $request->input('prportion');
            $info = $info . 'prportion => ' . $request->input('prportion') . ', ';
        }
        $data["updated_at"] = date('Y-m-d H:i:s');
        $info = $info . 'updated_at => ' . date('Y-m-d H:i:s') . ', ';
        $info = $info . "}";
        Log::info($info);
        $result->update($data);
        return $this->success();
    }
    public function delete(Request $request) {

        $validator = Validator::make($request->all(), [
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('projects');
        if ($request->has('id'))
            $result = $result->where('projects.id', '=', $request->input('id'));
        $result->delete();
        return $this->success();
    }
    
    public function shop_search(Request $request) {

        $validator = Validator::make($request->all(), [
            'offset' => 'required|integer|min:0',
            'length' => 'required|integer|min:1',
            'name' => 'string|max:100',
            'label_id' => 'integer|min:0',
            'sponsor' => 'string|max:100',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('projects')
            ->select([
                'projects.id as id',
                'projects.name as name',
                'projects.desc as desc',
                'projects.start_time as start_time',
                'projects.end_time as end_time',
                'projects.onsale as onsale',
                'projects.prportion as prportion',
                'labels_label_id.id as label_id',
                'labels_label_id.name as label_name',
                'projects.sponsor as sponsor',
                'projects.organizer as organizer',
                'projects.period as period',
                'projects.start_stydy_time as start_stydy_time',
                'projects.end_study_time as end_study_time',
            ]);
        $result = $result->leftJoin('labels as labels_label_id', 'labels_label_id.id', '=', 'projects.label_id');
        $result = $result->where('projects.shop_id', '=', $this->token->shop_id);
        if ($request->has('name'))
            $result = $result->where('projects.name', 'like', '%'.$request->input('name').'%');
        if ($request->has('label_id'))
            $result = $result->where('projects.label_id', '=', $request->input('label_id'));
        if ($request->has('sponsor'))
            $result = $result->where('projects.sponsor', 'like', '%'.$request->input('sponsor').'%');
        $result = $result->orderBy('projects.id', 'desc');
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
