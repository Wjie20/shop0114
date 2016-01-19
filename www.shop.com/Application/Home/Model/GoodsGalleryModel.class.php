<?php
/**
 * Created by PhpStorm.
 * User: wangjie
 * Date: 2016/1/19
 * Time: 21:28
 */

namespace Home\Model;


use Think\Model;

class GoodsGalleryModel extends Model
{
    /**
     * 根据商品id查询对应的相册的path,存入一个一维数组并返回
     * @param $goods_id
     * @return array
     */
    public function getGoodsGalleries($goods_id)
    {
       $rows = $this->field('path')->where(array('goods_id' => $goods_id))->select();
       return array_column($rows,'path');
    }
}