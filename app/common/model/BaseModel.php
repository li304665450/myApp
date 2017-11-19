<?php
/**
 * Created by PhpStorm.
 * User: 30466
 * Date: 2017/11/8
 * Time: 16:59
 */

namespace app\common\model;

use think\Model;

class BaseModel extends Model
{
    //开启自动添入时间
    protected $autoWriteTimestamp = 'datetime';

    /**
     * 数据表添加记录
     * @param $data 添加数据数组
     * @return id  添加后的记录id
     */
    public function add($data){
        if (!is_array($data)){
            exception('非法参数');
        }

        $this->allowField(true)->save($data);
        return $this->id;
    }

    /**
     * 数据列表主查询方法，支持检索
     * @param array $limt 分页要求
     * @param array $where 检索条件
     * @param array $order 排序方式
     * @return mixed list数据列表 count结果条数 pageTotal总页数
     */
    public function getAll($limit = [],$where = [],$order = []){

        //查询开始点
        $start = ($limit['page'] - 1) * $limit['size'];

        //符合条件的分页数据列表
        $result['list'] = $this->where($where)
            ->order($order)
            ->limit($start,$limit['size'])
            ->select();

        //未分页的记录总条数
        $result['count'] = $this->where($where)
            ->count();

        //分页的总页数
        $result['pageTotal'] = ceil($result['count']/$limit['size']);

        //当前页数
        $result['curr'] = $limit['page'];

        return $result;

    }

}