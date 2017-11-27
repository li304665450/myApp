<?php
/**
 * Created by PhpStorm.
 * User: 30466
 * Date: 2017/11/27
 * Time: 10:15
 */

namespace app\api\controller\v1;

use app\api\controller\Base;

class Cat extends Base {

    /**
     * 获取栏目信息
     * @return \think\response\Json
     */
    public function index(){

        $cats = config('cat.lists');

        foreach ($cats as $key => $value){
            $result[] = [
                'catid'    => $key,
                'catname'  => $value
            ];
        }

        return apiResult(1,'OK',$result,203);
    }
}