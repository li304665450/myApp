<?php
/**
 * Created by PhpStorm.
 * User: 30466
 * Date: 2017/11/8
 * Time: 17:51
 */

namespace app\common\validate;
use think\Validate;

class News extends Validate
{
    protected $rule = [
        'title' => 'require|max:20',
        'small_title' => 'require|max:20',
    ];

}