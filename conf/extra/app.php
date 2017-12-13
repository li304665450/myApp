<?php
/**
 * Created by PhpStorm.
 * User: 30466
 * Date: 2017/10/19
 * Time: 14:53
 */
return [
    'password_pre_halt'  => '_#string_try',//密码加密盐
    'aeskey' => 'ssg445566appccaa',//aes密钥
    'app_types' => [//服务端设备类型
        'ios',
        'android'
    ],
    'app_sign_time' => 1000000,//sign授权码过期时间
    'app_sign_cache_time' => 20,//sign授权码缓存失效时间
    'login_timeout_days' => 7,
    'default_username_prefix' => 'yiplay-粉'
];