<?php
/**
 * Created by PhpStorm.
 * User: 30466
 * Date: 2017/10/16
 * Time: 19:13
 */
namespace app\common\validate;

use think\Validate;

/**
 *后台管理员验证
 */
class AdminUser extends Validate{

    protected $rule = [
        'login_name' => 'require|max:20',
        'password' => 'require|max:20',
    ];

}
