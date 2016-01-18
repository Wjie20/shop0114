<?php
namespace Admin\Controller;


use Think\Controller;

class GoodsController extends BaseController
{
    protected $meta_title = '商品';
    protected $usePostParams = true;

    /**
     * 用于被子类覆盖的钩子方法,子类覆盖它提供查询数据的条件
     * @param $wheres
     */
    protected function _setWheres(&$wheres)
    {
        //根据商品品牌id,添加根据品牌id查询商品的条件
        $brand_id = I('get.brand_id');
        if (!empty($brand_id)) {
            $wheres['obj.brand_id'] = $brand_id;
        }
        //根据商品供货商id,添加根据供货商id查询商品的条件
        $supplier_id = I('get.supplier_id');
        if (!empty($supplier_id)) {
            $wheres['obj.supplier_id'] = $supplier_id;
        }
        //根据商品分类id,添加根据供货商id查询商品的条件
        $goods_category_id = I('get.goods_category_id');
        if (!empty($goods_category_id)) {
            $goodsCategoryModel = D('GoodsCategory');
            $leafIds = $goodsCategoryModel->getLeaf($goods_category_id);
            $wheres['obj.goods_category_id'] = array('in',$leafIds);
        }

    }


    /**
     * 覆盖BaseController中的该钩子方法
     * 在商品列表页面展示之前,提供index.html所需的数据
     */
    protected function _before_index_view()
    {
        //查询所有品牌数据,用作在商品展示的时候,按条件搜索
        $brandModel = D('Brand');
        $brands = $brandModel->getList('id,name');
        $this->assign('brands', $brands);
        //查询所有供货商数据,用作在商品展示的时候,按条件搜索
        $supplierModel = D('Supplier');
        $suppliers = $supplierModel->getList('id,name');
        $this->assign('suppliers', $suppliers);
        //查询所有商品分类的树状结构数据,用作在商品展示的时候,按条件搜索
        $goodsCategoryModel = D('GoodsCategory');
        $jsonData = $goodsCategoryModel->getTreeList('id,name,parent_id',true);
        $this->assign('jsonData', $jsonData);
    }


    /**
     * 覆盖BaseController中的该钩子方法,在编辑页面展示之前获取所有数据,用于回显
     */
    protected function _before_edit_view()
    {
        //获取商品分类的数据作为添加商品页面的树状结构的数据展示
        $goodsCategoryModel = D('GoodsCategory');
        $jsonData = $goodsCategoryModel->getTreeList('id,name', true);
//        $cat_names = $goodsCategoryModel->getList('name');
        $this->assign('jsonData', $jsonData);
        //获取商品品牌的数据作为添加商品页面的下拉列表数据展示
        $brandModel = D('Brand');
        $brands = $brandModel->getList('id,name');
        $this->assign('brands', $brands);
        //获取商品供货商的数据作为添加商品页面的下拉列表数据展示
        $supplierModel = D('Supplier');
        $suppliers = $supplierModel->getList('id,name');
        $this->assign('suppliers', $suppliers);

        //查询会员等级信息回想到添加商品的页面
        $memberLevelModel = D('MemberLevel');
        $members = $memberLevelModel->field('id,name')->select();
        $this->assign('members', $members);

        //获取当前编辑时传递的id,如果有get方式传递id  说明现在执行的操作是编辑.
        $id = I('get.id');
        if (!empty($id)) {
            $goodsIntroModel = M('GoodsIntro');
            //使用动态查询的方法,获取当前商品在goods_intro表中的简介,分配到页面.用作商品编辑时的回显
            $intro = $goodsIntroModel->getFieldByGoods_id($id, 'intro');
            $this->assign('intro', $intro);
            //查询当前商品对应的不同会员等级的会员价格作为编辑回显
            $goodsMemberPriceModel = D('GoodsMemberPrice');
            $member_info = $goodsMemberPriceModel->getGoodsMemberInfo($id);
            $this->assign('member_info', $member_info);
            //查询出当前商品对应的相册表中的数据,用作商品编辑回显
            $goodsGalleryModel = M('GoodsGallery');
            $goods_galleries = $goodsGalleryModel->where(array('goods_id' => $id))->field('id,path')->select();
            $this->assign('goods_galleries', $goods_galleries);

            //查询当前商品对应的文章数据用作回显
            $goodsArticleModel = D('GoodsArticle');
            $articles = $goodsArticleModel->getGoodsArticle($id);
            $this->assign('articles', $articles);

        }

    }


}