<?php
/**
 * Created by PhpStorm.
 * User: wangjie
 * Date: 2016/1/18
 * Time: 13:14
 */

namespace Admin\Model;


use Think\Model;

class GoodsArticleModel extends BaseModel
{

    /**
     *  根据当前商品的id,查询出对应的文章信息,用作商品编辑时的数据回显
     * @param $goods_id
     * @return mixed
     */
    public function getGoodsArticle($goods_id)
    {
        $this->alias('obj');
        $this->join("__ARTICLE__ AS a ON obj.`article_id` = a.`id`");
        $this->field('obj.article_id,a.name as article_name')->where(array('goods_id' => $goods_id));
        $articles = $this->select();
        return $articles;
    }


}