<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

    /**
     * @param $status 业务状态码
     * @param $msg 提示信息
     * @param array $data 数据
     * @param int $httpCode http状态码
     * @return \think\response\Json
     */
    function apiResult($status, $msg, $data = [], $httpCode = 200){
        $result = [
            'status' => $status,
            'msg' => $msg,
            'data' => $data
        ];
        return json($result, $httpCode);
    }
