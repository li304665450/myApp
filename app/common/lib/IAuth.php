<?php
/**
 * Created by PhpStorm.
 * User: 30466
 * Date: 2017/10/19
 * Time: 14:22
 */
namespace app\common\lib;

use think\Cache;

/**
 * Class IAuth
 * @package app\common\lib
 */
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

        return $str;
    }

    /**
     * 检查sign是否正常
     * @param $header header信息
     * @return bool
     */
    public static function checkSignPass($header){

        //解密
        $str = (new Aes())->decrypt($header['sign']);

        if (empty($str)){
            return false;
        }

        //将解密后的http参数转为数组
        parse_str($str,$arr);

        if (!is_array($arr)){
            return false;
        }
        //校验sign中的did和header中传递的did
        if (empty($arr['did']) || $arr['did'] != $header['did']){
            return false;
        }
        //时间校验，大于配置时间失效
        if (time() - ceil($arr['time'] / 1000) > config('app.app_sign_time')){
            return false;
        }
        //sign唯一判断
        if (Cache::get($header['sign'])){
            return false;
        }

        return true;
    }

    /**
     * 设置app登陆token - 唯一性的
     * @param string $phone 用户电话号码
     * @return string 加密串
     */
    public static function setAppLoginToke($phone = ''){
        $str = md5(uniqid(md5(microtime(true)),true));
        return sha1($str.$phone);
    }

}