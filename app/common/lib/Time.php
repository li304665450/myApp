<?php
/**
 * Created by PhpStorm.
 * User: 30466
 * Date: 2017/11/24
 * Time: 12:59
 */

namespace app\common\lib;

/**
 * app时间类
 * Class Time
 * @package app\common\lib
 */
class Time{

    /**
     * 获取13位时间戳
     * @return string
     */
    public static function getThirteenTime(){
        $micro = explode(' ', microtime());
        return  $micro[1] . ceil($micro[0]*1000);
    }

}