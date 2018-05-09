<?php
namespace app\admin\controller;

use app\admin\controller\Base;

/**
 * 后台入口基类
 * Class Index
 * @package app\admin\controller
 */
class Index extends Base
{

    /**
     * 后台入口
     * @return mixed
     */
    public function index()
    {
       return $this->fetch();
    }

    /**
     * 登陆初始页面
     * @return mixed
     */
     public function welcome()
     {

         $news = model('AdminDictionary');
//         $news = new \app\common\model\News();
//         var_dump($news->getAll());
//         die;
//         return $news->getAll();
         return apiResult(1,'hao',$news->dictionary);

//        return 111;

         //取栏目类型
//         $where['type'] = ['lt', 2];

         //栏目列表
//         return model('AdminDictionary')->getAll($where);
     }

}
