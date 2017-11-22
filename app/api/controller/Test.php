<?php
/**
 * Created by PhpStorm.
 * User: 30466
 * Date: 2017/11/22
 * Time: 11:31
 */

namespace app\api\controller;


use think\Controller;

class Test extends Controller
{
    public function index(){
        return [
            'sssg',
            'aaaaf'
        ];
    }

    public function configshow(){
        return config();
    }

    public function  read($id){
        echo $id;
        exit();
    }

}