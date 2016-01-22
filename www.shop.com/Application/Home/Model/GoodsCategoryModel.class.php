<?php
/**
 * Created by PhpStorm.
 * User: wangjie
 * Date: 2016/1/18
 * Time: 23:24
 */

namespace Home\Model;


use Think\Model;

class GoodsCategoryModel extends Model
{
    /**
     * 按条件查询所有数据
     * 先从redis的缓存中获取商品分类数据,如果有获取到数据,就使用那个缓存中的数据.如果没有,再到数据库中读取
     * @return mixed
     */
    public function getList()
    {
//        S('GOODS_CATEGORY',null);
//        exit;
        $rows = S('GOODS_CATEGORY');
        if (empty($rows)) {
            $rows = $this->field('id,name,parent_id,level')->where(array('status' => 1))->order('lft')->select();
            S('GOODS_CATEGORY', $rows);
        }
        return $rows;
    }

    public function getParents($goods_category_id)
    {
        $sql = "select parent.id,parent.name from goods_category as parent,goods_category as child where parent.lft <= child.lft and parent.rght >= child.rght and child.id = {$goods_category_id} order by parent.lft";
        $rows = $this->query($sql);
//        echo $this->_sql();
//        dump($rows);
//        exit;
        return $rows;

    }
}