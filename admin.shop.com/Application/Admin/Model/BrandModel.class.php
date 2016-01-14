<?php
namespace Admin\Model;


use Think\Model;

class BrandModel extends BaseModel
{
    // 每个表单都有自己的验证规则
    protected $_validate = array(
        array('name','require','名称不能够为空'),
array('site_url','require','网址不能够为空'),
array('logo','require','logo不能够为空'),
array('sort','require','排序不能够为空'),
array('status','require','状态不能够为空'),


    );
}