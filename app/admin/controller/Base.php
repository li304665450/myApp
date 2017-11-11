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

    /**
     * 删除记录方法
     * @param int $id 删除的记录id
     * @return json 接口返回值，包括操作码和类型提示
     */
    public function deleteAjax($id = 0){

        if (empty($id)){
            return $this->result(input('param.'),300,'ID不合法');
        }

        //获取调用方法当前控制器名
        $model = $this->model ? $this->model : request()->controller();

        try{
            $reult = model($model)->save(['status' => -1], ['id' => $id]);
        }catch (\Exception $e){
            return $this->result($e,400,'数据库异常');
        }

        return $this->result($reult,200,$id.'已删除!');
    }
}