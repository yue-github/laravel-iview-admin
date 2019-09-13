<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Validator;

class FileController extends Controller
{
    public function upload_banner(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }
        $fileName = "";
        $fileCharater = $request->file('file');
        if ($fileCharater->isValid()) { //判断文件是否有效
            //获取文件的扩展名
            $ext = $fileCharater->getClientOriginalExtension();
            //把扩展名改为小写
            $ext = strtolower($ext);
            //获取文件的绝对路径
            $path = $fileCharater->getRealPath();
            //定义文件名
            $fileName = date('Y-m-d-h-i-s') . '.' . $ext;
            //存储文件。disk里面的local。总的来说，就是调用disk模块里的local配置
            try{
                \Illuminate\Support\Facades\Storage::disk('public_' . 'banner')->put($fileName, file_get_contents($path));
                return $this->success($fileName);
            }catch(\Exception $e){
                return $this->fails("文件上传失败！！");
            }
        }
        return $this->fails();
    }
    public function upload_img(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }
        $fileName = "";
        $fileCharater = $request->file('file');
        if ($fileCharater->isValid()) { //判断文件是否有效
            //获取文件的扩展名
            $ext = $fileCharater->getClientOriginalExtension();
            //把扩展名改为小写
            $ext = strtolower($ext);
            //获取文件的绝对路径
            $path = $fileCharater->getRealPath();
            //定义文件名
            $fileName = date('Y-m-d-h-i-s') . '.' . $ext;
            //存储文件。disk里面的local。总的来说，就是调用disk模块里的local配置
            try{
                \Illuminate\Support\Facades\Storage::disk('public_' . 'img')->put($fileName, file_get_contents($path));
                return $this->success($fileName);
            }catch(\Exception $e){
                return $this->fails("文件上传失败！！");
            }
        }
        return $this->fails();
    }
    public function upload_excel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }
        $fileName = "";
        $fileCharater = $request->file('file');
        if ($fileCharater->isValid()) { //判断文件是否有效
            //获取文件的扩展名
            $ext = $fileCharater->getClientOriginalExtension();
            //把扩展名改为小写
            $ext = strtolower($ext);
            //获取文件的绝对路径
            $path = $fileCharater->getRealPath();
            //定义文件名
            $fileName = date('Y-m-d-h-i-s') . '.' . $ext;
            //存储文件。disk里面的local。总的来说，就是调用disk模块里的local配置
            try{
                \Illuminate\Support\Facades\Storage::disk('public_' . 'excel')->put($fileName, file_get_contents($path));
                return $this->success($fileName);
            }catch(\Exception $e){
                return $this->fails("文件上传失败！！");
            }
        }
        return $this->fails();
    }
    public function upload_music(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }
        $fileName = "";
        $fileCharater = $request->file('file');
        if ($fileCharater->isValid()) { //判断文件是否有效
            //获取文件的扩展名
            $ext = $fileCharater->getClientOriginalExtension();
            //把扩展名改为小写
            $ext = strtolower($ext);
            //获取文件的绝对路径
            $path = $fileCharater->getRealPath();
            //定义文件名
            $fileName = date('Y-m-d-h-i-s') . '.' . $ext;
            //存储文件。disk里面的local。总的来说，就是调用disk模块里的local配置
            try{
                \Illuminate\Support\Facades\Storage::disk('public_' . 'music')->put($fileName, file_get_contents($path));
                return $this->success($fileName);
            }catch(\Exception $e){
                return $this->fails("文件上传失败！！");
            }
        }
        return $this->fails();
    }
    public function upload_work(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }
        $fileName = "";
        $fileCharater = $request->file('file');
        if ($fileCharater->isValid()) { //判断文件是否有效
            //获取文件的扩展名
            $ext = $fileCharater->getClientOriginalExtension();
            //把扩展名改为小写
            $ext = strtolower($ext);
            //获取文件的绝对路径
            $path = $fileCharater->getRealPath();
            //定义文件名
            $fileName = date('Y-m-d-h-i-s') . '.' . $ext;
            //存储文件。disk里面的local。总的来说，就是调用disk模块里的local配置
            try{
                \Illuminate\Support\Facades\Storage::disk('public_' . 'work')->put($fileName, file_get_contents($path));
                return $this->success($fileName);
            }catch(\Exception $e){
                return $this->fails("文件上传失败！！");
            }
        }
        return $this->fails();
    }
    public function upload_class(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->fails($validator->errors()->all());
        }
        $fileName = "";
        $fileCharater = $request->file('file');
        if ($fileCharater->isValid()) { //判断文件是否有效
            //获取文件的扩展名
            $ext = $fileCharater->getClientOriginalExtension();
            //把扩展名改为小写
            $ext = strtolower($ext);
            //获取文件的绝对路径
            $path = $fileCharater->getRealPath();
            //定义文件名
            $fileName = date('Y-m-d-h-i-s') . '.' . $ext;
            //存储文件。disk里面的local。总的来说，就是调用disk模块里的local配置
            try{
                \Illuminate\Support\Facades\Storage::disk('public_' . 'class')->put($fileName, file_get_contents($path));
                return $this->success($fileName);
            }catch(\Exception $e){
                return $this->fails("文件上传失败！！");
            }
        }
        return $this->fails();
    }
    public function import_users(Request $request) 
    {
        $result = \App\Utils\FileUtil::import_user($request);
        return $this->success($result);
    }
    public function import_products(Request $request) 
    {
        $result = \App\Utils\FileUtil::import_products($request);
        return $this->success($result);
    }
}
