<?php
namespace Admin\Model;


use Think\Model;
use Think\Page;

class GoodsModel extends BaseModel
{
    // 每个表单都有自己的验证规则
    protected $_validate1 = array(
        array('name', 'require', '商品名称不能够为空'),
        array('short_name', 'require', '简称不能够为空'),
        array('sn', 'require', '货号不能够为空'),
        array('goods_category_id', 'require', '商品分类不能够为空'),
        array('brand_id', 'require', '商品品牌不能够为空'),
        array('supplier_id', 'require', '供货商不能够为空'),
        array('shop_price', 'require', '本店售价不能够为空'),
        array('market_price', 'require', '市场售价不能够为空'),
        array('goods_status', 'require', '商品状态不能够为空'),
        array('status', 'require', '是否显示不能够为空'),


    );





    /**
     * 根据表单复选框提交的数据(数组),使用二进制相或计算商品的状态值.
     */
    private function handleGoodsStatus()
    {
        $goods_status = $this->data['goods_status'];
        $status = 0;
        foreach ($goods_status as $val) {
            $status = $status | $val;
        }
        $this->data['goods_status'] = $status;
    }

    /**
     * 根据新增商品的id和请求中的会员价格数据,当前商品各个会员价格的数据
     * @param $prices
     * @param $id
     * @return array
     */
    private function handleGoodsMemberPrice($prices, $id)
    {
        $data = array();
        //将要写入的数据组装成一个二维数组,在批量添加
        foreach ($prices as $member_level_id => $price) {
            $data[] = array('goods_id' => $id, 'member_level_id' => $member_level_id, 'price' => $price);
        }
        return $data;

    }

    /**
     * ,根据新增商品的id 和上传商品相册成功后的路径,处理商品相册数据.
     * @param $paths
     * @param $id
     * @return array
     */
    private function handleGoodsGallery($paths, $id)
    {
        $data = array();
        foreach ($paths as $path) {
            $data[] = array('goods_id' => $id, 'path' => $path);
        }
        return $data;
    }

    /**
     * 根据当前商品id 和传递的当前商品对应的文章的id数组,计算成要想goods_article表中添加的数据,并返回
     * @param $article_id
     * @param $goods_id
     * @return array
     */
    private function handleGoodsArticle($article_id, $goods_id)
    {
        $data = array();
        foreach ($article_id as $aId) {
            $data[] = array('goods_id' => $goods_id, 'article_id' => $aId);
        }
        return $data;
    }

    /**
     * 添加新商品,同时操作多张表,使用事务
     * 先向goods表中插入基本数据,在根据返回当前新增数据的id更新货号
     * 再将当前商品的id和简介写入goods_intro表中
     * @param mixed|string $requestData
     * @return bool|mixed
     */
    public function add($requestData)
    {

        $this->startTrans();
        //计算商品状态,
        $this->handleGoodsStatus();
        //添加商品基本信息,返回新增商品的id
        $id = parent::add();
        if ($id === false) {
            $this->error = ' 添加商品时出错!';
            $this->rollback();
            return false;
        }
        //计算商品货号,年月日加9位数的当前商品的id,id的位数不足用0填充
        $goods_sn = date('Ymd') . str_pad($id, 9, "0", STR_PAD_LEFT);
        $result = parent::save(array('id' => $id, 'sn' => $goods_sn));
        if ($result === false) {
            $this->error = ' 更新商品货号时出错!';
            $this->rollback();
            return false;
        }

        //保存商品到goods表的同时,将商品简介保存到goods_intro表中
        $goodsIntroModel = M('GoodsIntro');
        $result = $goodsIntroModel->add(array('goods_id' => $id, 'intro' => $requestData['intro']));
        if ($result === false) {
            $this->error = ' 保存商品简介时出错!';
            $this->rollback();
            return false;
        }

        //保存当前商品的各个会员等级的售价,是已键值对的形式传递的数据: 键表示会员级别的id,值是该级别对应的售价,
        //一个商品对应三个级别的会员,有3个不同的会员价格,添加一个商品对应就有三条数据写入到goods_member_price表中.
        /**
         * array(3) {
         * [1] => string(2) "99"
         * [2] => string(2) "88"
         * [3] => string(2) "77"
         * }
         */
        //生成要添加的商品会员价格的数据
        $data = $this->handleGoodsMemberPrice($requestData['price'], $id);
        $goodsMemberPriceModel = M('GoodsMemberPrice');
        $result = $goodsMemberPriceModel->addAll($data);
        if ($result === false) {
            $this->error = ' 保存商品会员价格时出错!';
            $this->rollback();
            return false;
        }
        //将商品相册上传成功后的图片路径保存到goods_gallery表中
        $goods_gallery_data = $this->handleGoodsGallery($requestData['path'], $id);
        $goodsGalleryModel = M('GoodsGallery');
        $result = $goodsGalleryModel->addAll($goods_gallery_data);
        if ($result === false) {
//            $this->error = ' 保存商品相册时出错!';
//            $this->rollback();
//            return false;
        }
        //调用处理当前商品对应的文章的方法,获取数据添加到goods_article表中
        $data = $this->handleGoodsArticle($requestData['article_id'], $id);
        if (!empty($data)) {
            $goodsArticleModel = M('GoodsArticle');
            $result = $goodsArticleModel->addAll($data);
            if ($result === false) {
                $this->error = ' 保存商品文章时出错!';
                $this->rollback();
                return false;
            }
        }
        $this->commit();
        return $id;
    }

    /**
     *  修改商品信息
     * @param mixed|string $updateData
     * @return bool
     */
    public function save($updateData)
    {
//        dump($updateData);
//        exit;
        $this->startTrans();
        //修改goods表中的数据
        $id = $this->data['id'];
        $this->handleGoodsStatus();
        $result = parent::save();
//        echo $this->_sql();exit;
//        dump($result);exit;
        if ($result === false) {
            $this->error = '更新商品出错啦';
            $this->rollback();
            return false;
        }
        //根据goods_id修改goods_intro表中的数据
        $goodsIntroModel = M('GoodsIntro');
        $rst = $goodsIntroModel->save(array('goods_id' => $id, 'intro' => $updateData['intro']));
        if ($rst === false) {
            $this->error = '更新商品简介出错';
            $this->rollback();
            return false;
        }

        //修改商品会员价格,先将原来的各种会员对应的所有会员价格删除,再将更新的会员价格,添加到goods_member_price表中
        $goodsMemberPriceModel = M('GoodsMemberPrice');
        $result = $goodsMemberPriceModel->where(array('goods_id' => $id))->delete();
        if ($result === false) {
            $this->error = '删除商品会员价时出错';
            $this->rollback();
            return false;
        }
        $data = $this->handleGoodsMemberPrice($updateData['price'], $id);
        $result = $goodsMemberPriceModel->addAll($data);
        if ($result === false) {
            $this->error = '修改商品会员价时出错';
            $this->rollback();
            return false;
        }

        //将编辑后的图片路径再保存到goodsGallery表中
        $goodsGalleryModel = M('GoodsGallery');
        $data = $this->handleGoodsGallery($updateData['path'], $id);
        if ($data) {
            $result = $goodsGalleryModel->addAll($data);
            if ($result === false) {
                $this->error = '修改商品图片时出错';
                $this->rollback();
                return false;
            }
        }

        //将编辑后的商品的对应文章信息更新到goods_article表中,
        //先根据当前商品id,删除goods_article表中对应的文章,
        //再将新的文章数据更新到该表中.
        $goodsArticleModel = D('GoodsArticle');
        $result = $goodsArticleModel->where(array('goods_id' => $id))->delete();
        if ($result === false) {
            $this->error = '修改商品相关文章时出错';
            $this->rollback();
            return false;
        }
        //调用处理当前商品对应的文章的方法,获取数据添加到goods_article表中
        $data = $this->handleGoodsArticle($updateData['article_id'], $id);
        if (!empty($data)) {
            $result = $goodsArticleModel->addAll($data);
            if ($result === false) {
                $this->error = ' 保存商品文章时出错!';
                $this->rollback();
                return false;
            }
        }
        $this->commit();
        return $result;
    }

    /**
     *  覆盖父类中的钩子方法,
     *  使用连接查询,查询出商品对应的分类名称,品牌名称,商品供货商名称..用作商品列表页的展示
     */
    protected function _setModel()
    {
        $this->join("__GOODS_CATEGORY__ AS gc ON obj.`goods_category_id`=gc.`id` ");
        $this->join("__BRAND__ AS b ON obj. `brand_id` = b.`id`");
        $this->join("__SUPPLIER__ as s on obj.`supplier_id` = s.`id`");
        $this->field("obj.*,gc.name as goods_category_name,b.name as brand_name,s.name as supplier_name");
    }


}