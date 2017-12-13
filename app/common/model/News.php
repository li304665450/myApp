<?php
/**
 * Created by PhpStorm.
 * User: 30466
 * Date: 2017/11/8
 * Time: 16:57
 */

namespace app\common\model;


class News extends BaseModel
{

    /**
     * 状态获取器
     * @param $value
     * @return mixed
     */
    public function getStatusAttr($value)
    {
        $status = $this->dictionary['status'];
        return $status[$value];
    }

}