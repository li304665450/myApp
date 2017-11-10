<?php
/**
 * Created by PhpStorm.
 * User: 30466
 * Date: 2017/10/20
 * Time: 16:15
 */
namespace app\admin\controller;

use think\Controller;

class Base extends Controller{

    public $model = "";

    public function _initialize()
    {
        //获取用户session信息
        $user = session('adminuser','','myApp_admin');

        //没有登陆，跳转登陆页面
        if (!$user){
            $this->redirect('login/login');
        }
    }

    public function deleteAjax($id = 0){

        if (empty($id)){
            return $this->result('',500,'ID不合法');
        }

        //获取调用方法当前控制器名
        $model = $this->model ? $this->model : request()->controller();

        try{
            $reult = model('')->save(['status' => -1], ['id' => $id]);
        }catch (\Exception $e){
            return $this->result('',2004,'数据库异常');
        }

        return $this->result('',0000,$id.'已删除!');
    }
}