<?php
/**
 * Created by PhpStorm.
 * User: 30466
 * Date: 2017/12/4
 * Time: 13:19
 */

namespace app\admin\controller;


/**
 * 数据字典模块控制器
 * Class AdminDictionary
 * @package app\admin\controller
 */
class AdminDictionary extends Base{

    /**
     * 复写排序设置方法
     * 设置本控制器默认排序方式
     * @return array
     */
    public function setOrder()
    {
        //按排序字段升序
        $order = ['listorder' => 'asc'];

        return $order;
    }

    /**
     * 主页，主要做ztree
     * @return mixed
     */
    public function index(){
        return $this->fetch();
    }

    /**
     * 数据列表页
     * @return mixed
     */
    public function lists(){
        return $this->fetch();
    }

    /**
     * 详情页，添加/修改
     * @return mixed
     */
    public function edit(){
        return $this->fetch();
    }

    /**
     * @return mixed
     */
    public function getMenuAjax(){

        $order = $this->setOrder();

        $where = $this->setWhere();

        //取栏目类型
        $where['type'] = ['lt', 2];

        //栏目列表
        return model($this->getModel())->getAll($where,$order);

    }

}