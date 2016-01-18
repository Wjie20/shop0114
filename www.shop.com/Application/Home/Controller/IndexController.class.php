<?php
namespace Home\Controller;

use Think\Controller;

class IndexController extends Controller
{
    public function index()
    {
        //商品分类的展示
        $goodsCategoryModel = D('GoodsCategory');
        $goodsCategories = $goodsCategoryModel->getList();
        $this->assign('goodsCategories',$goodsCategories);
        $this->assign('meta_title','首页');
        $this->display('index');
    }
    public function lst()
    {

        $this->assign('is_hide',true);
        $this->assign('meta_title','商品列表');
        $this->display('lst');
    }
    public function goods()
    {
        $this->assign('is_hide',true);
        $this->assign('meta_title','商品详情');
        $this->display('goods');
    }
}