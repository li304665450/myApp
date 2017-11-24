<?php
/**
 * Created by PhpStorm.
 * User: 30466
 * Date: 2017/11/24
 * Time: 14:36
 */

namespace app\api\controller;

use think\Collection;


/**
 * 时间相关接口
 * Class Time
 * @package app\api\controller
 */
class Time extends Collection {

    /**
     * 获取服务端当前时间戳
     * @return int
     */
    public function getTimeStamp(){
        return time();
    }
}