<?php
/**
 * Created by PhpStorm.
 * User: 30466
 * Date: 2017/10/16
 * Time: 15:51
 */
namespace app\admin\controller;

use app\admin\controller\Base;
use app\common\lib\IAuth;

/**
 * 管理员模块控制器
 * Class Admin
 * @package app\admin\controller
 */
class Admin extends Base{

    /**
     * 添加管理员页面
     * @return mixed
     */
    public function add(){
        return $this->fetch();
    }

}
