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

    /**
     * 生成sign
     * @param array $data
     * @return string
     */
    public static function setSign($data = []){
        //按字段排序
        ksort($data);
        //拼接字符串数据
        $str = http_build_query($data);
        //aes加密
        $str = (new Aes())->encrypt($str);
        //所有字符转换大写
//        $str = strtoupper($str);

        return $str;
    }

}