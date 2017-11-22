<?php
/**
 * Created by PhpStorm.
 * User: 30466
 * Date: 2017/11/22
 * Time: 16:57
 */

namespace app\common\lib\exception;


use think\Exception;
use Throwable;

/**
 * 自定义接口报错类
 * Class ApiException
 * @package app\common\lib\exception
 */
class ApiException extends Exception {

    public  $message = '';
    public $httpCode = 500;
    public $code = 0;

    public function __construct($message = "", $httpCode, $code = 0)
    {
        $this->message = $message;
        $this->httpCode = $httpCode;
        $this->code = $code;
    }
}