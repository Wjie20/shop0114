<?php
namespace Home\Controller;

use Think\Controller;

class IndexController extends Controller
{

    /**
     *  每个页面都需要用到商品分类的数据,将其写在这个方法中,
     *  当基础控制器Controller的构造方法被调用时,自动懂调用这个方法.
     */
    public function _initialize()
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
    }

    /**
     * 前台首页
     */
    public function index()
    {
        //根据商品状态查询对应的5条商品数据用作前台的显示(疯狂抢购,热卖商品...)
        $goodsModel = D('Goods');
        $goods1status = $goodsModel->getGoodsByGoodsStatus(1);
        $goods2status = $goodsModel->getGoodsByGoodsStatus(2);
        $goods4status = $goodsModel->getGoodsByGoodsStatus(4);
        $goods8status = $goodsModel->getGoodsByGoodsStatus(8);
        $goods16status = $goodsModel->getGoodsByGoodsStatus(16);
        //使用关联数组的形式,将各个商品状态对应查询读出的数据分配到页面
        $this->assign(
            array(
                'goods1status' => $goods1status,
                'goods2status' => $goods2status,
                'goods4status' => $goods4status,
                'goods8status' => $goods8status,
                'goods16status' => $goods16status,
            )
        );
        $this->assign('meta_title', '首页');
        $this->display('index');
    }

    public function lst()
    {

        $this->assign('is_hide', true);
        $this->assign('meta_title', '商品列表');
        $this->display('lst');
    }

    /**
     * 商品详情页
     * @param $id
     */
    public function goods($id)
    {

        //根据当前商品的id,查询当前商品的数据用作goods.html页面的数据展示
        $goodsModel = D('Goods');
        $goods = $goodsModel->getGoodsById($id);
        $this->assign($goods);
        //根据当前商品的分类id查询出当前商品的所有父级分类,用作商品页的面包屑导航
        $goodsCategoryModel = D('GoodsCategory');
        $parents = $goodsCategoryModel->getParents($goods['goods_category_id']);
        $this->assign('parents', $parents);
        $this->assign('is_hide', true);
        $this->assign('meta_title', '商品详情');

        //根据当前商品id查询出该商品的logo图片以及相册图片,logo放在第一个的位置
        $goodsGalleryModel = D('GoodsGallery');

        $goodsGalleries = $goodsGalleryModel->getGoodsGalleries($id);
        //将商品的logo图片作为该图片数组的第一个元素,
        array_unshift($goodsGalleries, $goods['logo']);
        $this->assign('goodsGalleries', $goodsGalleries);

        //最近浏览商品,将用户浏览的商品数据保存在cookie中.
        $histories = cookie('histories');
        if (empty($histories)) {
            $histories = array();
        } else {
            $histories = unserialize($histories);
        }
        foreach ($histories as $key => $history) {
            if ($id == $history['id']) {
                unset($histories[$key]);
                break;
            }
        }
        $this->assign('histories', $histories);
        $history = array(
            'id' => $id,
            'name' => $goods['name'],
            'logo' => $goods['logo'],
        );
        array_unshift($histories, $history);
        $histories = serialize($histories);
        cookie('histories', $histories);

        $this->display('goods');
    }

}