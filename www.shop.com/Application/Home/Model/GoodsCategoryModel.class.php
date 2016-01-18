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
     * @return mixed
     */
    public function getList()
    {
        $rows = $this->field('id,name,parent_id,level')->where(array('status' => 1))->order('lft')->select();
        return $rows;
    }
}