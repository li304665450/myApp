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

    public function postTest(){
        return dump(input('post.'));
    }

    /**
     * 添加管理员页面
     * @return mixed
     */
    public function add(){
        return $this->fetch();
    }

    /**
     * 管理员添加操作方法
     */
    public function addAjax(){

        //判断是否为post提交
        if (request()->isPost()){
            //接收post传输数据
            $data = input('post.');

            //表单后台二次验证
            $validate = validate('AdminUser');
            if (!$validate->check($data)){
                return $validate->getError();
            }

            //加密密码
            $data['password'] = IAuth::setPassword($data['password']);
            $data['status'] = 1;

            //插入管理员信息
            try{
                $id = model('AdminUser')->add($data);
            }catch (\Exception $e){
                return $e->getMessage();
            }

            //如果插入成功
            if ($id){
                return '管理员'.$id.'添加成功';
            }else {
                return '添加失败';
            }
        }else{
            return '数据传输异常';
        }

    }

}
