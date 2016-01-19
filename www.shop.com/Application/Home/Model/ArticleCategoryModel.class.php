<?php
/**
 * Created by PhpStorm.
 * User: wangjie
 * Date: 2016/1/19
 * Time: 12:41
 */

namespace Home\Model;


use Think\Model;

class ArticleCategoryModel extends Model
{
    public function getArticleCategory()
    {
        $rows = S('ARTICLE_CATEGORY');
        if (empty($rows)) {
            $rows = $this->field('id,name')->where(array('status' => 1, 'is_help' => 1))->select();
            S('ARTICLE_CATEGORY', $rows);
        }
        return $rows;
    }
}