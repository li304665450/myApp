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
    public function index()
    {
       return $this->fetch();
    }

    /**
     * 登陆初始页面
     * @return mixed
     */
     public function welcome()
     {

         return model('News')->getAll();


         //取栏目类型
//         $where['type'] = ['lt', 2];

         //栏目列表
//         return model('AdminDictionary')->getAll($where);
     }

}
