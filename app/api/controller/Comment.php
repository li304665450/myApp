<?php
/**
 * Created by PhpStorm.
 * User: 30466
 * Date: 2017/11/22
 * Time: 10:33
 */

namespace app\api\controller;


use think\Controller;

class Comment extends Controller{

    public function test(){
        $json = [
            id => 1,
            name => 'Tom'
        ];
        return $json;
    }

}