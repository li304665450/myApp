<?php
/**
 * Created by PhpStorm.
 * User: 30466
 * Date: 2017/11/22
 * Time: 11:31
 */

namespace app\api\controller;


use app\common\lib\exception\ApiException;

class Test extends Base
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
        $data = input('post.');
        if ($data['mt'] != 1){
            throw new ApiException('数据不合法唉！',402);
        }
       return $this->apiResult(1,'ok',input('post.'),201);
    }

    public function showConfig(){
        return config();
    }
}