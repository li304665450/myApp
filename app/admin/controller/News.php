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

        $data = input('param.');

        $limit['page'] = !empty($data['page']) ? $data['page'] : 1;//页数
        $limit['size'] = !empty($data['size']) ? $data['size'] : config('paginate.list_rows');//每页条数

        //检索条件
        $where['status'] = ['neq', -1];

        //排序要求
        $order = ['id' => 'desc'];

        //查询数据表
        try{
            $result = model('News')->getAll($limit,$where,$order);
        }catch (\Exception $e){
            return $e->getMessage();
        }

        return $this->fetch('',[
            'list' => $result['list'],
            'pageTotal' => $result['pageTotal'],
            'count' => $result['count'],
            'curr' => $limit['page']
        ]);
    }

    /**
     * 数据列表获取方法
     * @return string
     */
    public function listAjax(){

        $data = input('param.');

        $limit['page'] = !empty($data['page']) ? $data['page'] : 1;//页数
        $limit['size'] = !empty($data['size']) ? $data['size'] : config('paginate.list_rows');//每页条数

        //检索条件
        $where['status'] = ['neq', -1];
        if (!empty($data['title'])){
            $where['title'] = $data['title'];
        }

        //排序要求
        $order = ['id' => 'desc'];

        //查询数据表
        try{
            $result = model('News')->getAll($limit,$where,$order);
        }catch (\Exception $e){
            return $e->getMessage();
        }

//        dump($result['list']);
//        foreach ($result['list'] as $value){
//            echo $value;
//        }
        return json_encode($result);
    }

    /**
     * 添加页面
     * @return mixed
     */
    public function add(){
        return $this->fetch();
    }

    /**
     * 新闻添加操作方法
     * @return string
     */
    public function addAjax(){

        if (request()->isPost()){

        $data = input('post.');

        //表单后台二次验证
        $validate = validate('News');
        if (!$validate->check($data)){
            return $validate->getError();
        }

        $data['status'] = 1;

        //将提交表单信息插入新闻表
        try{
            $id = model('News')->add($data);
        }catch (\Exception $e){
            return $e->getMessage();
        }

            //如果插入成功
            if ($id){
                return '新闻'.$id.'添加成功';
            }else {
                return '添加失败';
            }

        }else{
            return '数据异常';
        }
    }
}