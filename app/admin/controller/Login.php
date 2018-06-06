<?php
/**
 * Created by PhpStorm.
 * User: 30466
 * Date: 2017/10/20
 * Time: 16:53
 */
namespace app\admin\controller;

use think\Controller;
use app\common\lib\IAuth;

class Login extends Controller{

    /**
     * 登陆页面
     * @return mixed
     */
    public function login(){
        return $this->fetch();
    }

    /**
     * 登陆验证方法
     * @return string 提示信息
     */
    public function loginAjax(){

        //判断是否为post提交
        if (request()->isPost()) {

            //获取提交数据
            $data = input('post.');

            //验证码验证
            if (!captcha_check($data['code'])) {
                return '验证码不正确';
            }

            //按用户名查询用户表
            try {
                $user = model('AdminUser')->get(['login_name' => $data['login_name']]);
            } catch (\Exception $e) {
                return $e->getMessage();
            }

                //判断用户是否存在
                if (!$user || $user->status != 1) {
                    return '用户名不存在';
                }

                //判断密码是否正确
                if ($user->password != IAuth::setPassword($data['password'])) {
                    return '密码不正确';
                }

                //更新最后登陆信息
                $update = [
                    'last_login_time' => date('Y-m-d H:i:s',time()),
                    'last_login_ip' => request()->ip()
                ];

            //更新最后登陆时间和ip
            try {
                model('AdminUser')->save($update, ['id' => $user->id]);
            } catch (\Exception $e) {
                return $e->getMessage();
            }

            //设置session信息
            session('adminuser', $user, 'myApp_admin');

            //登陆成功，跳转页面
            return '登陆成功';
        }else{
            return '数据传输异常';
        }

    }

    /**
     * 退出登陆
     */
    public function loginout(){

        //清空session
        session(null,'myApp_admin');

        $this->redirect('login');
    }

    /**
     * 手动登陆后台
     * @return string
     */
    public function loginHead(){
        //设置session信息
        session('adminuser', 'api', 'myApp_admin');

        //登陆成功，跳转页面
        return '登陆成功';
    }

}