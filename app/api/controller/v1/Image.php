<?php
/**
 * Created by PhpStorm.
 * User: 30466
 * Date: 2017/12/1
 * Time: 17:15
 */

namespace app\api\controller\v1;


use app\api\controller\AuthBase;
use app\common\lib\Upload;

/**
 * 客户端图片处理基础类
 * Class Image
 * @package app\api\controller\v1
 */
class Image extends AuthBase{

    /**
     * 图片上传方法
     * @return \think\response\Json
     */
    public function save(){
        $image = Upload::image();
        if ($image){
            return apiResult(1,'上传成功！',config('qiniu.image_url').$image);
        }
    }
}