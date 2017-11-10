<?php
/**
 * Created by PhpStorm.
 * User: 30466
 * Date: 2017/11/6
 * Time: 14:56
 */
namespace app\admin\controller;

use app\admin\controller\Base;
use think\Request;
use app\common\lib\Upload;
use Qiniu\Auth;
use Prophecy\Prophet;

/**
 * 后台图片上传基类
 * Class Image
 * @package app\admin\controller
 */
class Image extends Base{

    /**
     * 本地图片上传方法
     * @return string
     */
    public function upload(){

        //获取前端上传对象信息
        $file = Request::instance()->file('file');

        //上传文件，返回回调信息包括图片地址
        $info = $file->move('upload');

        //视成功与否返回接口数据
        if ($info && $info->getPathname()){
            $data = [
                'status' => 1,
                'message' => 'OK',
                'data' => $info->getPathname()
            ];
        }else{
            $data = [
                'status' => 0,
                'message' => 'Upload is error'
            ];
        }
        return json_encode($data);
    }

    /**
     * 七牛云图片上传方法
     * @return string
     */
    public function uploadForQiniu(){

        try{
            $image = Upload::image();
        }catch (\Exception $exception){
            return json_encode(['status' => 0, 'message' => 'Upload is error']);
        }

        //判断上传回调信息，返回接口信息
        if ($image){
            $data = [
                'status' => 1,
                'message' => 'OK',
                'data' => config('qiniu.image_url').'/'.$image
            ];
        }else{
            $data = [
                'status' => 0,
                'message' => 'Upload is error'
            ];
        }
        return json_encode($data);

    }

}