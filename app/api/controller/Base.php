<?php
/**
 * Created by PhpStorm.
 * User: 30466
 * Date: 2017/11/22
 * Time: 16:07
 */

namespace app\api\controller;


use app\common\lib\Aes;
use app\common\lib\IAuth;
use think\Controller;

class Base extends Controller{

    /**
     * 初始化方法
     */
    public function _initialize()
    {
        $this->testSign();
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

    public function testSign(){
        $data = [
            'did' => '12345g',
            'version' => 1
        ];
        $sign = IAuth::setSign($data);
        $str = 'FZzH02huZnKg63XF8gunWH37QMSPeui0q8Ep769vEMY=';
        $sign = (new Aes())->decrypt($str);
        echo $sign;exit();
    }

    public function saveAes(){
        $aes = new Aes();
        $str = $aes->encrypt('?id=3&name=Tom&sex=man');
        echo $aes->decrypt($str);exit();
        halt($aes->decrypt($str));
        halt($str);
    }
}