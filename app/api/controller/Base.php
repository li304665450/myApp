<?php
/**
 * Created by PhpStorm.
 * User: 30466
 * Date: 2017/11/22
 * Time: 16:07
 */

namespace app\api\controller;


use app\common\lib\Aes;
use think\Controller;

class Base extends Controller{

    /**
     * 初始化方法
     */
    public function _initialize()
    {
        $this->saveAes();
    }

    /**
     * @param $status 业务状态码
     * @param $msg 提示信息
     * @param array $data 数据
     * @param int $httpCode http状态码
     * @return \think\response\Json
     */
    public function apiResult($status, $msg, $data = [], $httpCode = 200){
        $result = [
            'status' => $status,
            'msg' => $msg,
            'data' => $data
        ];
        return json($result, $httpCode);
    }

    public function checkRequestAuth(){

        //获取header中的数据
        $header = request()->header();

        halt($header);
    }

    public function saveAes(){
        $aes = new Aes();
        halt($aes->encrypt('?id=3&name=Tom&sex=man'));
    }
}