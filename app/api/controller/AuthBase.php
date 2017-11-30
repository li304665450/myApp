<?php
/**
 * Created by PhpStorm.
 * User: 30466
 * Date: 2017/11/30
 * Time: 10:57
 */

namespace app\api\controller;
use app\common\lib\Aes;
use app\common\lib\exception\ApiException;
use app\common\model\User;
use think\Exception;


/**
 * 客户端Auth登陆权限控制基础类
 * 1、需要登陆权限的接口都需继承此类
 * 2、判定 access_user_token 是否合法
 * 3、用户信息 -> user
 * Class AuthBase
 * @package app\api\controller
 */
class AuthBase extends Base {

    /**
     * 登录用户的基本信息
     * @var array
     */
    public $user = [];

    /**
     * 初始化方法
     */
    public function _initialize()
    {
        parent::_initialize();

        if (!$this->isLogin()){
            throw new ApiException('您还未登陆！', 401);
        }
    }

    /**
     * 判断是否登陆
     * @return boolean
     */
    public function isLogin(){

        //获取header中的数据
        $this->header = request()->header();

        if (empty($this->header['access_user_token'])){
            return false;
        }

        //对access_user_token解密
        $access_token = (new Aes())->decrypt($this->header['access_user_token']);

        //access_token不存在或不包含||
        if (empty($access_token) || !preg_match('/||/', $access_token)){
            return false;
        }

        list($token, $id) = explode('||', $access_token);

        $user = User::get(['token' => $token]);

        //用户是否存在且状态是否正常
        if (!$user || $user['status'] != 1){
            return false;
        }

        //登陆是否过期
        if (time() > $user['time_out']){
            return false;
        }

        $this->user = $user;//存储用户基本信息

        return true;
    }

}