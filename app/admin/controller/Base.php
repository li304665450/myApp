<?php
/**
 * Created by PhpStorm.
 * User: 30466
 * Date: 2017/10/20
 * Time: 16:15
 */
namespace app\admin\controller;

use app\common\controller\BaseController;

/**
 * 后台系统基础控制器
 * Class Base
 * @package app\admin\controller
 */
class Base extends BaseController {

    //自定义构造函数
    public function _initialize()
    {
        //获取用户session信息
        $user = session('adminuser','','myApp_admin');

        //没有登陆，跳转登陆页面
        if (!$user){
            $this->redirect('login/login');
        }
    }

}