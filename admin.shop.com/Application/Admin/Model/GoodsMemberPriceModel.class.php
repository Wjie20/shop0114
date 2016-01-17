<?php
/**
 * Created by PhpStorm.
 * User: wangjie
 * Date: 2016/1/16
 * Time: 13:57
 */

namespace Admin\Model;


use Think\Model;

class GoodsMemberPriceModel extends Model
{
    public function getGoodsMemberInfo($id)
    {
        $member_info = $this->field('member_level_id,price')->where(array('goods_id' => $id))->select();
        //将该二维数组的member_level_id最为一个数组
        $member_level_ids = array_column($member_info, 'member_level_id');
        //将该二维数组的price最为一个数组
        $prices = array_column($member_info, 'price');
        //将两个数组合并,member_level_id作为键,price作为值,组成一个新的数组,并分配到页面
        $member_info = array_combine($member_level_ids, $prices);
        return $member_info;
    }

}