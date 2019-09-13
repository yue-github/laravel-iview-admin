<?php 
 
namespace App\Http\Controllers;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller{
    public function init(){
         date_default_timezone_set('PRC');
    }
    public function getList(){
         return DB::table('banner')
         ->orderBy('id','asc')->get();
    }
    // 修改轮播图
    public function setList(){
        DB::table('banner')->delete();
        DB::table('banner')->insert(request('json'));
        return DB::table('banner')->get();
    }
    public function saveImg(Request $request){
         $result=DB::table('banner')
            ->where(['id'=>request('id')])
            ->get();
            $boo=!$result->isEmpty();
            if($boo){
                $bannerSrc=json_decode($result,true)[0]['url'];
                $url=trim($bannerSrc);
                if($url){
                    $url=substr($bannerSrc, strlen(request('domain')));
                    if(file_exists($url)!=''){
                        unlink($url);
                    } 
                }
            }
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
            $path =request('domain').'uploads/'.$filename;
            $getId=null;
            if(!$boo){
                 DB::table('banner')->insert(['url'=>$path]);
                 $getId=json_decode(DB::table('class')->where(['url'=>$path])->get(['id']),true)[0]['id'];
            }else{
                 DB::table('banner')->where(['id'=>request('id')])->update(['url'=>$path]);
            }
           $id=$getId?$getId:request('id');
            return response()->json(['url'=>$path,'id'=>$id]); 
        }
    }
}





 