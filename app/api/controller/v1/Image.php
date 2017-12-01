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

class Image extends AuthBase{

    public function save(){
        print_r($_FILES);
        $image = Upload::image();
    }
}