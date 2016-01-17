<?php
namespace Admin\Model;


use Think\Model;

class ArticleCategoryModel extends BaseModel
{
    // 每个表单都有自己的验证规则
    protected $_validate = array(
        array('name','require','分类名称不能够为空'),
array('is_help','require','帮助分类不能够为空'),
array('status','require','是否显示不能够为空'),


    );
}