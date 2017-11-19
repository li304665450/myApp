<?php
/**
 * Created by PhpStorm.
 * User: 30466
 * Date: 2017/10/25
 * Time: 16:13
 */
namespace app\admin\controller;

use app\admin\controller\Base;

/**
 * 新闻模块控制器
 * Class News
 * @package app\admin\controller
 */
class News extends Base{

    /**
     * 首页列表页面
     * @return int
     */
    public function index(){
        return $this->fetch();
    }

    /**
     * 记录编辑页面
     * @return mixed
     */
    public function edit(){
        return $this->fetch();
    }

}