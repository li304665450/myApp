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
         $user = model('User');
         $where['status'] = ['neq', -1];
//         $order = ['id' => 'desc', 'update_time' => 'asc'];
         $order = ['id' => 'desc'];
         $order['update_time'] = 'asc';
         $arr = ['a','b','b','d','e','f'];
//         array_push($order, ['update_time' => 'asc']);
//         $order[] = ['update_time' => 'asc'];
//         halt($order);
//         print_r($order);;exit();
         $user->where($where)->order($order)->select();
         return $user->getLastSql();
     }

}
