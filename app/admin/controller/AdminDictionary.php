<?php
/**
 * Created by PhpStorm.
 * User: 30466
 * Date: 2017/12/4
 * Time: 13:19
 */

namespace app\admin\controller;


/**
 * 数据字典模块控制器
 * Class AdminDictionary
 * @package app\admin\controller
 */
class AdminDictionary extends Base{

    /**
     * 主页，主要做ztree
     * @return mixed
     */
    public function index(){
        return $this->fetch();
    }

    /**
     * 数据列表页
     * @return mixed
     */
    public function lists(){
        return $this->fetch();
    }

}