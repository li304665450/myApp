<?php
/**
 * Created by PhpStorm.
 * User: 30466
 * Date: 2017/11/30
 * Time: 14:37
 */

namespace app\api\controller\v1;


use app\api\controller\Base;
use app\common\lib\Aes;
use app\common\lib\exception\ApiException;
use app\common\lib\IAuth;
use app\common\lib\Sms;
use app\common\model\User;

/**
 * 客户端登陆基础类
 * Class Login
 * @package app\api\controller\v1
 */
class Login extends Base {

    /**
     * 登陆接口
     * @return \think\response\Json
     * @throws ApiException
     */
    public function save(){

        //必须为post提交
        if (!request()->isPost()){
            return apiResult(0,'没有权限', '',403);
        }

        $param = input('param.');

        if (empty($param['phone'])){
            return apiResult(0,'手机号不合法', '',404);
        }

        if (empty($param['code'])){
            return apiResult(0,'验证码不合法', '',404);
        }

        $code = Sms::getSmsCode();

        if ($code != $param['code']){
            return apiResult(0,'验证码不存在', '',404);
        }

        $user = User::get(['phone' => $param['phone']]);

        //已注册，直接登陆
        if ($user){
            $result = [
                'token' => (new Aes())->encrypt($user['token'].'||'.$user['id'].'||'.time())
            ];
            return apiResult(1,'ok', $result,200);
        }

        //第一次登陆，注册数据
        $data = [
            'token' => IAuth::setAppLoginToke(),
            'time_out' => strtotime('+'.config("app.login_timeout_days").' days'),
            'name' => config('app.default_username_prefix').$param['phone'],
            'status' => 1,
            'phone' => $param['phone']
        ];

        try{
            $id = model('User')->add($data);
        }catch (\Exception $e){
            throw new ApiException('数据库异常:');
        }

        if ($id){
            $result = [
                'token' => (new Aes())->encrypt($data['token'].'||'.$id.'||'.time())
            ];
            return apiResult(1,'ok', $result,200);
        }

        return apiResult(0,'error', '',300);

    }

}