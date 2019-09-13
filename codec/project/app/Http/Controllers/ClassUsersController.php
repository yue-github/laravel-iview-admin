<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Validator;

class ClassUsersController extends Controller
{
    public function create(Request $request) {

        $validator = Validator::make($request->all(), [
            'class_id' => 'required|integer|min:0',
            'user_id' => 'required|integer|min:0',
            'purchased_id' => 'required|integer|min:0',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        DB::table('class_users')->insert([
            'class_id' => $request->input('class_id'),
            'user_id' => $request->input('user_id'),
            'purchased_id' => $request->input('purchased_id'),
        ]);
        return $this->success();
    }
    public function delete(Request $request) {

        $validator = Validator::make($request->all(), [
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('class_users');
        if ($request->has('class_id'))
            $result = $result->where('class_users.class_id', '=', $request->input('class_id'));
        if ($request->has('user_id'))
            $result = $result->where('class_users.user_id', '=', $request->input('user_id'));
        $result->delete();
        return $this->success();
    }
    
    public function search(Request $request) {

        $validator = Validator::make($request->all(), [
            'offset' => 'required|integer|min:0',
            'length' => 'required|integer|min:1',
            'class_id' => 'integer|min:0',
            'user_id' => 'integer|min:0',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('class_users')
            ->select([
                'class_class_id.id as class_id',
                'class_class_id.name as class_name',
                'users_user_id.id as id',
                'users_user_id.name as name',
                'users_user_id.company as company',
                'users_user_id.phone as phone',
                'users_user_id.email as email',
                'users_user_id.balance as balance',
                'evaluates_user_id.content as evaluates_content',
                'evaluates_user_id.grade as evaluates_grade',
                'purchased_purchased_id.id as purchased_id',
                'purchased_purchased_id.rate as rate',
                'purchased_purchased_id.score as score',
                'purchased_purchased_id.progress as progress',
                'purchased_purchased_id.product_id as product_id',
            ]);
        $result = $result->leftJoin('class as class_class_id', 'class_class_id.id', '=', 'class_users.class_id');
        $result = $result->leftJoin('users as users_user_id', 'users_user_id.id', '=', 'class_users.user_id');
        $result = $result->leftJoin('evaluates as evaluates_user_id', 'evaluates_user_id.user_id', '=', 'users_user_id.id');
        $result = $result->leftJoin('purchased as purchased_purchased_id', 'purchased_purchased_id.id', '=', 'class_users.purchased_id');
        if ($request->has('class_id'))
            $result = $result->where('class_users.class_id', '=', $request->input('class_id'));
        if ($request->has('user_name'))
            $result = $result->where('users_user_id.name', 'like', '%'.$request->input('user_name').'%');
        if ($request->has('user_id'))
            $result = $result->where('class_users.user_id', '=', $request->input('user_id'));
        if ($request->has('user_phone'))
            $result = $result->where('users_user_id.phone', 'like', '%'.$request->input('user_phone').'%');
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
    
    public function get_class_by_purchased_id(Request $request) {

        $validator = Validator::make($request->all(), [
            'purchased_id' => 'integer|min:0',
        ]);
        if($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }

        $result = DB::table('class_users')
            ->select([
                'class_class_id.id as id',
                'class_class_id.name as name',
                'class_class_id.shop_id as shop_id',
                'class_class_id.product_id as product_id',
                'class_class_id.task_id as task_id',
                'class_class_id.activitie_id as activitie_id',
                'class_class_id.start_time as start_time',
                'class_class_id.instructor_id as instructor_id',
                'class_class_id.state as state',
            ]);
        $result = $result->leftJoin('class as class_class_id', 'class_class_id.id', '=', 'class_users.class_id');
        if ($request->has('purchased_id'))
            $result = $result->where('class_users.purchased_id', '=', $request->input('purchased_id'));
        $result = $result->first();
        return $this->success($result);
    }
    public function create_all(Request $request){
       $validator = Validator::make($request->all(), [
            'product_id' => 'required|integer|min:0',
            'class_id' => 'required|integer|min:0'
        ]);
        if($validator->fails()) {
            return $this->fails( $validator->errors()->all());
        }
        //班级里已有的成员
        $class_user_id_arr = DB::table('class_users')
                ->leftJoin('class', 'class_users.class_id', '=', 'class.id')
                ->where('class.product_id', $request->input('product_id'))
                ->lists('class_users.user_id');

        $users = DB::table('purchased')
            ->select(['purchased.id as purchased_id',
                      'purchased.product_id as product_id',
                      'orders.owner_id as user_id'])
            ->leftJoin('orders', 'orders.id', '=', 'purchased.order_id')
            ->leftJoin('users', 'users.id', '=', 'orders.owner_id')
            ->where('purchased.product_id', $request->input('product_id'))
            ->where('orders.state', '=', 2)
            ->whereNotIn('orders.owner_id',$class_user_id_arr)
            ->get();

        if(count($users) == 0){
            return $this->fails();
        }
        
        $data = array();
        foreach ($users as $user){
            $class_user = [
                'class_id' => $request->input('class_id'),
                'user_id' => $user->user_id,
                'purchased_id' => $user->purchased_id
            ];
            array_push($data, $class_user);
        }

        DB::table('class_users')->insert($data);

        return $this->success();
    }
}
