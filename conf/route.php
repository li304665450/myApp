<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

use think\Route;

Route::resource('test', 'api/test');

//栏目
Route::resource(':ver/cat', 'api/:ver.cat');

//登陆
Route::resource('login/:ver', 'api/:ver.login');

//图片
Route::resource('image/:ver', 'api/:ver.image');
