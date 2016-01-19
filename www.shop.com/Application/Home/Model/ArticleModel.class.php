<?php
/**
 * Created by PhpStorm.
 * User: wangjie
 * Date: 2016/1/19
 * Time: 13:52
 */

namespace Home\Model;


use Think\Model;

class ArticleModel extends Model
{
    public function getHlepArticle()
    {
        $rows = S('HELP_ARTICLE');
        if (empty($rows)) {
            $this->alias('obj');
            $this->join('__ARTICLE_CATEGORY__  AS ac ON obj.`article_category_id`=ac.`id`');
            $this->field('obj.id,obj.article_category_id,obj.name');
            $rows = $this->select();
            S('HELP_ARTICLE', $rows);
        }
        return $rows;
    }
}