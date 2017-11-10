<?php
/**
 * Created by PhpStorm.
 * User: 30466
 * Date: 2017/10/19
 * Time: 14:22
 */
namespace app\common\lib;

class IAuth{

    /**
     * 设置密码加密
     * @param $data
     * @return string
     */
    public static function setPassword($data){
        return md5($data.config('app.password_pre_halt'));
    }
}