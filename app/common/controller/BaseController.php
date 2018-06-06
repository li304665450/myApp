<?php
/**
 * Created by PhpStorm.
 * User: 30466
 * Date: 2017/12/4
 * Time: 17:01
 */

namespace app\common\controller;


use app\common\lib\exception\ApiException;
use think\Controller;

class BaseController extends Controller {

    /**
     * 当前控制器名
     * @var string
     */
    public $model = "";

    /**
     * 获取调用方法当前控制器名
     * @return string|\think\Request
     */
    public function getModel(){
        return $this->model ? $this->model : request()->controller();
    }

    /**
     * 数据操作方法，有id时为更新，没有id直接添加
     * @return \think\response\Json 接口回掉信息
     * @throws ApiException
     */
    public function saveAjax(){

        //必须为post提交
        if (!request()->isPost()){
            throw new ApiException('没有权限',403);
        }

        $data = input('post.');

        if (empty($data['id'])){

            //表单数据后台验证
            //$this->saveValidate($this->getModel(),$data);

            //将提交表单信息插入表
            $result = model($this->getModel())->add($data);
        }else{
            //将表单上传数据中的id和其他数据分离
            $param = $this->unsetOfId($data);

            //更新表中数据
            $result = model($this->getModel())->saveOfUpdate($param);
        }

        //数据转为json格式返给前端
        return json($result,200);

    }

    /**
     * 数据列表获取方法
     * @return string 列表数据json格式
     */
    public function listAjax(){

        //整理筛选获取条件
        $param = $this->splitData(input('post.'));

        //查询数据表
        $result = model($this->getModel())->getlist($param['limit'], $param['where'], $param['order']);

        //数据转为json格式返给前端
        return json($result,200);
    }

    /**
     * @param int $id 记录id
     * @return \think\response\Json
     * @throws ApiException
     */
    public function infoAjax($id = 0){

        //提交数据必须有id
        if (!$id){
            throw new ApiException('数据不合法！',303);
        }

        //获取记录详情数据
        $result = model($this->getModel())->getInfor($id);

        //数据转为json格式返给前端
        return json($result,200);
    }

    /**
     * 设置共有检索条件，设置条件可被覆盖
     * @return mixed
     */
    public function setWhere(){

        //状态不为-1
        $where['status'] = ['neq', -1];

        return $where;
    }

    /**
     * 设置默认排序规则，设置规则不可被覆盖
     * @return array
     */
    public function setOrder(){

        //按id倒叙
        $order = ['id' => 'desc'];

        return $order;
    }

    /**
     * 表单数据后台验证方法
     * @param string $model 模板名
     * @param array $data 要做验证的数据列表
     * @throws ApiException
     */
    public function saveValidate($model,$data){

        $validate = validate($model);
        if (!$validate->check($data)){
            throw new ApiException('非法数据！',303);
        }
    }

    /**
     * 将表单上传数据中的id和其他数据分离
     * @param array $data 表单提交数据
     * @return mixed 分离后数据
     */
    public function unsetOfId($data){

        $result['id'] = $data['id'];

        foreach ($data as $k => $v){
            if ($k == 'id'){
                unset($data[$k]);
            }
        }

        $result['data'] = $data;

        return $result;
    }

    /**
     * 对列表页传入参数进行整理匹配
     * @param array $data 传入数组
     * @return mixed 返回整理后参数
     */
    public function splitData($data = []){

        $limit['page'] = !empty($data['page']) ? $data['page'] : 1;//页数
        $limit['size'] = !empty($data['size']) ? $data['size'] : config('paginate.list_rows');//每页条数

        $where = $this->setWhere();//设置默认筛选条件

        $order = $this->setOrder();//设置默认排序规则

        //循环扩展检索条件
        foreach ($data as $k => $v){

            //将页数和每页条数以及参数值为空的先去掉
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
                    case 'order'://排序规则
                        $order[$arr[1]] = $v;
                        break;
                    default://其他标准类型如：eq,neq,egt,lt,elt
                        $where[$arr[1]] = [$arr[0], $v];
                        break;
                }
            }
        }

        $result['limit'] = $limit;//分页条件
        $result['where'] = $where;//检索条件
        $result['order'] = $order;//排序规则

        return $result;

    }

}