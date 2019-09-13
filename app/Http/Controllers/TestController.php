<?php 

namespace App\Http\Controllers;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\Builder;
class TestController extends Controller{
	public function init(){
		date_default_timezone_set('PRC');
    }
    public function t(){
        return 1;
    }
	public function test(){
        return response()->json(['status'=>200,'msg'=>'数据获取成功了啊coral3.com']);
    // return DB::table('web_visit')->get();
        //   return '施乐华123';
	}
    public function getTask(){
        $this->init();
        $arr=array('李杰文','温锦鹏','覃永涛','万人玮','张建峰','郑观广','林栩','梁光','萧达赓','施乐华','林敦怀','张桂焕','王伟斌','曾育涵','王浪','庄秀钿','许嘉伟');
         if(request('password')=='love3 '){
                shuffle($arr);
                $random_keys=array_rand($arr,5);
                shuffle($random_keys);
                shuffle($random_keys);
                 $result=DB::table('task')->insert(['name1'=>$arr[$random_keys[0]],'name2'=>$arr[$random_keys[1]],'name3'=>$arr[$random_keys[2]],'name4'=>$arr[$random_keys[3]],'name5'=>$arr[$random_keys[4]],'date'=>date('Y-m-d H:i:s',time())]);
                 if($result){
                   return DB::table('task')
                   ->orderBy('id','desc')
                   ->get();
                 }
         }else if(request('password')=='lowingshan'){
            return response()->json(['status'=>503,'msg'=>'密码换了，新密码有6位']);
         }else if(request('password')==' 333 '){
                shuffle($arr);
                $random_keys=array_rand($arr,5);
                shuffle($random_keys);
                shuffle($random_keys);
                 $result=DB::table('task')->insert(['name1'=>$arr[$random_keys[2]],'date'=>'请总结']);
                 if($result){
                   return DB::table('task')
                   ->orderBy('id','desc')
                   ->get();
                 }
         }else{
            return response()->json(['status'=>503,'msg'=>'密码错误，请慎重']);
         }
    }
    public function getName(){
        return DB::table('task')
                   ->orderBy('date','desc')
                   ->get();
    }
	public function upload(Request $request){
        // 文件是否上传成功
		$file = $request->file('file');
		 
		if ($file->isValid()) {

            // 获取文件相关信息
            $originalName = $file->getClientOriginalName(); // 文件原名
            $ext = $file->getClientOriginalExtension();     // 扩展名
            $realPath = $file->getRealPath();   //临时文件的绝对路径
            $type = $file->getClientMimeType();     // image/jpeg
            
            // 上传文件
            $filename = date('Y-m-d-H-i-s') . '-' . uniqid() . '.' . $ext;
            // 使用我们新建的uploads本地存储空间（目录）
            //这里的uploads是配置文件的名称
            $bool = Storage::disk('uploads')->put($filename, file_get_contents($realPath));
            $path ='/uploads/'.$filename;
            return response()->json(['url'=>$path]); 
        }
    }
    // testEnter
    public function testEnter(){
        $maxId=DB::table('user_visit')->max('id');
        $result=DB::table('user_visit')->where(['id'=>$maxId])->get(['visit_count']);
        return $result;
    }
    // 需要token
    public function needToken(){
        return '测试token';
    }
    public function getTitle(){
        return response()->json([
            'title'=>'听一种回忆',
            'url'=>'https://coral3.com/audio/1967.mp3',
            'banner'=>[
                    ['url'=>'https://coral3.com/img/coral2.png'],
                    ['url'=>'https://coral3.com/img/coral1.png'],
                    ['url'=>'https://coral3.com/img/coral3.png']
                ]
            ]);
    }
    public function enterLove(){
        $this->init();
        $password=request('password');
        if($password!='  '){
            DB::table('visit_log')->insert(['name'=>$password,'time'=>date('Y-m-d H:i:s',time())]);
        }
        if($password=='卢泳珊'||$password=='泳珊'||$password=='ysr'||$password=='吴同岳'||$password=='  '){
            if($password!='  '){
                DB::table('who_visit')->insert(['name'=>$password,'time'=>date('Y-m-d H:i:s',time())]);
            }
            return response()->json(['status'=>200,'msg'=>'欢迎进入']);
        }else{
            return response()->json(['status'=>503,'msg'=>'输入的密码有误']);
        }
        
    }




}





?>