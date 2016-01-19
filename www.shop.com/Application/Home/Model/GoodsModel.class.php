<?php
/**
 * Created by PhpStorm.
 * User: wangjie
 * Date: 2016/1/19
 * Time: 17:29
 */

namespace Home\Model;


use Think\Model;

class GoodsModel extends Model
{
    /**
     *  根据商品状态,查询对应的5条数据
     * @param $status
     * @param int $num
     * @return mixed
     */
    public function getGoodsByGoodsStatus($status, $num = 5)
    {
        $rows = $this->field('id,name,shop_price,logo')->where(array('status' => 1))->where("goods_status&{$status}={$status}")->limit($num)->select();
        return $rows;
    }

    public function getGoodsById($goods_id)
    {
        $this->alias('obj');
        $this->join('__BRAND__ as b ON obj.brand_id = b.id');
        $this->join('__GOODS_INTRO__ as gi ON obj.id=gi.goods_id');
        $this->field('obj.*,b.name as brand_name,gi.intro');
        $row = $this->where(array('obj.id' => $goods_id))->find();
        return $row;
    }
}