<?php
/**
 * Created by PhpStorm.
 * User: 30466
 * Date: 2017/10/25
 * Time: 16:13
 */
namespace app\admin\controller;

use app\admin\controller\Base;

/**
 * 新闻模块控制器
 * Class News
 * @package app\admin\controller
 */
class News extends Base{

    /**
     * 首页列表页面
     * @return int
     */
    public function index(){
        return $this->fetch();
    }

    /**
     * 数据列表获取方法
     * @return string 列表数据json格式
     */
    public function listAjax(){

        $data = input('param.');

        $limit['page'] = !empty($data['page']) ? $data['page'] : 1;//页数
        $limit['size'] = !empty($data['size']) ? $data['size'] : config('paginate.list_rows');//每页条数

        //检索条件
        $where['status'] = ['neq', -1];
        if (!empty($data['title'])){
            $where['title'] = ['like','%'.$data['title'].'%'];
        }

        //排序要求
        $order = ['id' => 'desc'];

        //查询数据表
        try{
            $result = model('News')->getAll($limit,$where,$order);
        }catch (\Exception $e){
            return $this->result($e,400,'数据库异常');
        }

        //当前页数
        $result['curr'] = $limit['page'];

        //数据转为json格式返给前端
        return $this->result(json_encode($result),200,'数据获取成功');
    }

    /**
     * 记录编辑页面
     * @return mixed
     */
    public function edit(){
        return $this->fetch();
    }

}