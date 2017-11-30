<?php
/**
 * Created by PhpStorm.
 * User: 30466
 * Date: 2017/11/22
 * Time: 11:31
 */

namespace app\api\controller;


use app\common\lib\exception\ApiException;
use app\common\lib\IAuth;

class Test extends AuthBase
{
    public function index(){
        return [
            'sssg',
            'aaaaf'
        ];
    }

    public function update($id){
//        echo $id;exit();
        halt(input('put.'));
    }

    public function  save(){
//        model('aaa');
//        $data = input('post.');
//        if ($data['mt'] != 1){
//            throw new ApiException('数据不合法唉！',402);
//        }
       return apiResult(1,'ok', $this->user,201);
    }

    public function showConfig(){
        return config();
    }

    public function token(){
        return IAuth::setAppLoginToke(15600017090);
    }
}