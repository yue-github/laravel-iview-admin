<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use App\User;
class MessageController extends Controller
{
    // 错误信息保存
    public function getMessage(){
        return response()->json([
            'status'=>200,
            'unread'=>[
                ['msg_id'=>1,'tilte'=>'你好，这是消息1','create_time'=>'2019-6-5 5:00'],
                ['msg_id'=>2,'tilte'=>'你好，这是消息2','create_time'=>'2019-9-5 4:00']
            ],
            'readed'=>[
                ['msg_id'=>4,'tilte'=>'你好，这是消息1','create_time'=>'2019-6-5 5:00'],
                ['msg_id'=>3,'tilte'=>'你好，这是消息4','create_time'=>'2019-9-5 4:00']
            ],
            'trash'=>[
                ['msg_id'=>8,'tilte'=>'你好，这是消息1','create_time'=>'2019-6-5 5:00'],
                ['msg_id'=>9,'tilte'=>'你好，这是消息9','create_time'=>'2019-9-5 4:00']
            ]
        ]);
    }
    // 获取单条消息侧具内容
    public function getMessageContent(){
        $msg_id=request('msg_id');
        $content='你好，这是消息ID'.$msg_id.'的内容';
        return $content;
        // return response()->json([
        //     'title'=>$content,
        //     'create_time'=>'2019-8-'.$msg_id
        // ]);
    }
    // 标记已读信息，这边不操作数据库，流程应该是将未读信息从未读数据表中删除然后加入到已读表中
    public function setMessageRead(){
        return '标记已读信息，这边不操作数据库，流程应该是将未读信息从未读数据表中删除然后加入到已读表中';
    }
}