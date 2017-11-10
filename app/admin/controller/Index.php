<?php
namespace app\admin\controller;

use app\admin\controller\Base;

/**
 * 后台入口基类
 * Class Index
 * @package app\admin\controller
 */
class Index extends Base
{

    /**
     * 后台入口
     * @return mixed
     */
    public function index(){
       return $this->fetch();
    }

    /**
     * 登陆初始页面
     * @return mixed
     */
     public function welcome(){
//       return session('adminuser','','myApp_admin');
         return $this->fetch();
     }

}
