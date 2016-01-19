<?php
namespace Admin\Controller;


use Think\Controller;

class GoodsCategoryController extends BaseController
{
    protected $meta_title = '商品分类';

    /**
     * 重写父类中的获取数据的index方法,
     */
    public function index()
    {   //获取分类列表数据
        $rows = $this->model->getTreeList();
        cookie('__forward__', $_SERVER['REQUEST_URI']);
        $this->assign('rows', $rows);
        $this->assign('meta_title', $this->meta_title);
        //>>4.选择视图页面
        $this->display('index');
    }


    protected function _before_edit_view(){
        //获取有排列顺序的分类列表数据
        $jsonData = $this->model->getTreeList('id,name,parent_id',true);
        //将其转化成json格式的数据分配给浏览器
        $this->assign('jsonData', $jsonData);
    }

}