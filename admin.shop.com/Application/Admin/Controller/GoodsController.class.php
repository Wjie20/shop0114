<?php
namespace Admin\Controller;


use Think\Controller;

class GoodsController extends BaseController
{
    protected $meta_title = '商品';
    protected $usePostParams = true;

    protected function _before_edit_view()
    {
        //获取商品分类的数据作为添加商品页面的树状结构的数据展示
        $goodsCategoryModel = D('GoodsCategory');
        $jsonData = $goodsCategoryModel->getTreeList('id,name', true);
        $cat_names = $goodsCategoryModel->getList('name');
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

        //获取当前编辑时传递的id,如果有get方式传递id  说明现在执行的操作是编辑.查询出当前商品的简介,用于数据的回显
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
            $this->assign('goods_galleries',$goods_galleries);

        }

    }

}