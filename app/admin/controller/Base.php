<?php
/**
 * Created by PhpStorm.
 * User: 30466
 * Date: 2017/10/20
 * Time: 16:15
 */
namespace app\admin\controller;

use think\Controller;

class Base extends Controller{

    public $model = "";

    //自定义构造函数
    public function _initialize()
    {
        //获取用户session信息
        $user = session('adminuser','','myApp_admin');

        //没有登陆，跳转登陆页面
        if (!$user){
            $this->redirect('login/login');
        }
    }

    /**
     * 添加记录操作方法
     * @return string
     */
    public function addAjax(){

        if (request()->isPost()){

            $data = input('post.');

            //获取调用方法当前控制器名
            $model = $this->model ? $this->model : request()->controller();

            //表单后台二次验证
            $validate = validate($model);
            if (!$validate->check($data)){
                return $this->result(input('post.'),300,$validate->getError());
            }

            //将提交表单信息插入新闻表
            try{
                $id = model($model)->add($data);
            }catch (\Exception $e){
                return $this->result($e->getMessage(),400,'数据库异常');
            }

            //如果插入成功
            if ($id){
                return $this->result($id,200,$id.'添加成功');
            }else {
                return $this->result($id,500,'添加失败');
            }

        }else{
            return $this->result(input('param.'),300,'数据不合法');
        }
    }

    /**
     * 数据更新方法
     * @return json 接口返回值，包括操作码和类型提示
     */
    public function updateAjax(){

        if (request()->isPost()){

            $data = input('post.');

            $id = $data['id'];

            foreach ($data as $k => $v){
                if ($k == 'id'){
                    unset($data[$k]);
                }
            }

            //获取调用方法当前控制器名
            $model = $this->model ? $this->model : request()->controller();

            try{
                $reult = model($model)->save($data,['id' => $id]);
            }catch (\Exception $e){
                return $this->result($e->getMessage(),400,'数据库异常');
            }

            return $this->result($reult,200,'操作成功');

        }

        return $this->result(input('post.'),300,'数据不合法');

    }

    /**
     * 数据列表获取方法
     * @return string 列表数据json格式
     */
    public function listAjax(){

        $data = input('param.');

        $limit['page'] = !empty($data['page']) ? $data['page'] : 1;//页数
        $limit['size'] = !empty($data['size']) ? $data['size'] : config('paginate.list_rows');//每页条数

        //循环扩展检索条件
        foreach ($data as $k => $v){

            //将页数和每页条数先去掉
            if ($k == 'page' || $k == 'size' || empty($v)){
                unset($data[$k]);
                break;
            }

            //解析条件和字段名
            $arr = explode("-",$k);

            if (empty($arr[1])){
                $where[$arr[0]] = $v;
            }else{
                switch ($arr[0]){
                    case 'like':
                        $where[$arr[1]] = [$arr[0], '%'.$v.'%'];
                        break;
                    case 'likemore'://多字段或连接like
                        $condition = $arr[1];
                        for ($i = 2; $i < count($arr); $i++){
                            $condition .= '|'.$arr[$i];
                        }
                        $where[$condition] = ['like', '%'.$v.'%'];
                        break;
                    case 'eqmore'://多字段或连接等于
                        $condition = $arr[1];
                        for ($i = 2; $i < count($arr); $i++){
                            $condition .= '|'.$arr[$i];
                        }
                        $where[$condition] = $v;
                        break;
                    default:
                        $where[$arr[1]] = [$arr[0], $v];
                        break;
                }
            }
        }

        //补充检索条件
        $where['status'] = ['neq', -1];

        //排序要求
        $order = ['id' => 'desc'];

        //获取调用方法当前控制器名
        $model = $this->model ? $this->model : request()->controller();

        //查询数据表
        try{
            $result = model($model)->getAll($limit,$where,$order);
        }catch (\Exception $e){
            return $this->result($e,400,'数据库异常');
        }

        //数据转为json格式返给前端
        return $this->result(json_encode($result),200,'数据获取成功');
    }

    /**
     * 获取记录详情
     * @param int $id 记录id
     * @return string 列表数据json格式
     */
    public function infoAjax($id = 0){

        if ($id){

            //获取调用方法当前控制器名
            $model = $this->model ? $this->model : request()->controller();

            //按id查询记录详情
            try {
                $info = model($model)->get(['id' => $id]);
            } catch (\Exception $e) {
                return $this->result($e->getMessage(),400,'数据库异常');
            }

            //返回结果转为json格式
            $info = json_encode($info);

            //数据转为json格式返给前端
            return $this->result($info,200,'数据获取成功');

        }else{
            return $this->result(input('param.'),300,'数据不合法');
        }

    }

}