<?php
/**
 * Created by PhpStorm.
 * User: wangjie
 * Date: 2016/1/22
 * Time: 22:29
 */

namespace Home\Controller;


use Think\Controller;

class AddressController extends Controller
{
    public function index()
    {
        //商品分类的展示
        $goodsCategoryModel = D('GoodsCategory');
        $goodsCategories = $goodsCategoryModel->getList();
        $this->assign('goodsCategories', $goodsCategories);

        //获取文章分类数据,用作前台页面,页脚部分的数据展示
        $articleCategoryModel = D('ArticleCategory');
        $articleCategories = $articleCategoryModel->getArticleCategory();
        $this->assign('articleCategories', $articleCategories);
        //获取文章分类对应的帮助文章,用作显示数据
        $articleModel = D('Article');
        $helpArticles = $articleModel->getHlepArticle();
        $this->assign('helpArticles', $helpArticles);


        $this->assign('is_hide', true);
        $this->display('index');
    }
}