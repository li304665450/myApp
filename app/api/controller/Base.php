<?php
/**
 * Created by PhpStorm.
 * User: 30466
 * Date: 2017/11/22
 * Time: 16:07
 */

namespace app\api\controller;


use app\common\lib\exception\ApiException;
use app\common\lib\IAuth;
use app\common\lib\Time;
use think\Cache;
use think\Controller;

/**
 * api请求基类
 * Class Base
 * @package app\api\controller
 */
class Base extends Controller{

    /**
     * @var string header头信息
     */
    public $header = '';

    /**
     * 初始化方法
     */
    public function _initialize()
    {
        $this->checkRequestAuth();
//        $this->testSign();
    }

    /**
     * 请求合法性校验方法
     * @throws ApiException
     */
    public function checkRequestAuth(){

        //获取header中的数据
        $header = request()->header();

        //基础数据校验
        if (empty($header['sign'])){
            throw new ApiException('sign 不存在', 400);
        }

        //设备类型校验
        if (empty($header['app_type']) || !in_array($header['app_type'], config('app.app_types'))){
            throw new ApiException('app_type 不合法', 400);
        }

        //校验sign格式
        if (!IAuth::checkSignPass($header)){
            throw new ApiException('sign授权码校验失败', 401);
        }

        //对使用过的sign设置使用状态
        Cache::set($header['sign'], 1, config('app.app_sign_cache_time'));

        $this->header = $header;
    }

    public function testSign(){
        $data = [
            'did' => '12345g',
            'version' => 1,
            'time' => Time::getThirteenTime(),
        ];
//        halt($data);
        //emNTQ9Xhfmd3hd0pXWR3xMk5P0B8fGDJAfwNr7WIge3zhGh2n+dEtXXZfEUxlVgM
        $sign = IAuth::setSign($data);
        echo $sign;exit();
    }

}