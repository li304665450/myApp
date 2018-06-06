<?php
/**
 * Created by PhpStorm.
 * User: 30466
 * Date: 2017/11/7
 * Time: 11:20
 */

namespace app\common\lib;

//引入鉴权类
use  Qiniu\Auth;
//引入上传类
use  Qiniu\Storage\UploadManager;

/**
 * 七牛图片上传基础类库
 * Class Upload
 * @package app\common\lib
 */
class Upload
{

    public static function image(){

        if (!$_FILES['file']['tmp_name']){
            exception('上传图片不合法',404);
        }

        //七牛配置信息
        $config = config('qiniu');
        //上传临时文件名
        $file = $_FILES['file']['tmp_name'];
        //上传文件后缀名
        $pathinfo = pathinfo($file);
        $ext = $pathinfo['extension'];
        //构建鉴权对象
        $auth = new Auth($config['ak'], $config['sk']);
        //生成上传的token
        $token = $auth->uploadToken($config['bucket']);
        //上传到七牛后保存的文件名
        $key = date('Y').'/'.date('M').'/'.substr(md5($file),0,5).date('YmdHis').rand(0,9999).'.'.$ext;

        //初始化UploadManager类
        $uploadManager = new UploadManager();
        //执行上传，返回回调信息
        list($msg,$err) = $uploadManager->putFile($token, $key, $file);
        if (null !== $err){
            return null;
        }else{
            return $key;
        }
    }


}