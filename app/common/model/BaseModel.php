<?php
/**
 * Created by PhpStorm.
 * User: 30466
 * Date: 2017/11/8
 * Time: 16:59
 */

namespace app\common\model;

use app\common\lib\exception\ApiException;
use think\Model;

class BaseModel extends Model
{
    //开启自动添入时间
    protected $autoWriteTimestamp = 'datetime';

    /**
     * 数据表添加记录
     * @param $data 添加数据数组
     * @return array 回调数组包含添加后的记录id
     * @throws ApiException
     */
    public function add($data){

        try{
            $this->allowField(true)->save($data);
        }catch (\Exception $e){
            throw new ApiException('数据库异常！');
        }

        $result['id'] = $this->id;

        return result(1, '添加成功！', $this->returnSql($result));
    }

    /**
     * 更新数据
     * @param $data 要更新的数据
     * @return array 回调数组
     * @throws ApiException
     */
    public function saveOfUpdate($data){

        try{
            $result['data'] = $this->save($data['data'],['id' => $data['id']]);
        }catch (\Exception $e){
            throw new ApiException('数据库异常！');
        }

        return result(1, '更新成功！',$this->returnSql($result));
    }

    /** 获取全部数据列表，不分页
     * @param $where 检索条件，可选
     * @param $order 排序规则，可选
     * @return array list数据列表
     * @throws ApiException
     */
    public function getAll($where = [],$order = []){

        try{
            $data = empty($where) ? $this : $this->where($where);
            $data = empty($order) ? $data : $data->order($order);
            $result['list'] = $data->select();
        }catch (\Exception $e){
            throw new ApiException('数据库异常！');
        }

        return result(1, '成功！', $this->returnSql($result));
    }

    /**
     * 数据列表主查询方法，支持检索,分页，排序
     * @param array $limt 分页要求
     * @param array $where 检索条件
     * @param array $order 排序方式
     * @return array list数据列表 count结果条数 pageTotal总页数
     * @throws ApiException
     */
    public function getList($limit = [],$where = [],$order = []){

        //查询开始点
        $start = ($limit['page'] - 1) * $limit['size'];

        //未分页的记录总条数
        $result['count'] = $this->where($where)->count();

        //符合条件的分页数据列表
        try{
            $result['list'] = $this->where($where)
                ->order($order)
                ->limit($start,$limit['size'])
                ->select();
        }catch (\Exception $e){
            throw new ApiException('数据库异常！');
        }

        //分页的总页数
        $result['pageTotal'] = ceil($result['count']/$limit['size']);

        //当前页数
        $result['curr'] = $limit['page'];

        return result(1, '成功！', $this->returnSql($result));

    }

    /**
     * 按id查询记录详情
     * @param $id 记录id
     * @return array 数据记录详情
     * @throws ApiException
     */
    public function getInfor($id){

        try {
            $result['info'] = $this->get(['id' => $id]);
        } catch (\Exception $e) {
            throw new ApiException('数据库异常！');
        }

        return result(1, '成功！', $this->returnSql($result));
    }

    /**
     * 输出数据补加sql语句
     * @param $result
     * @return mixed
     */
    public function returnSql($result){

        //开启调试模式，输出数据带SQL语句
        if (config('app_debug')){
            $result['sql'] = $this->getLastSql();
        }
        return $result;

    }

}