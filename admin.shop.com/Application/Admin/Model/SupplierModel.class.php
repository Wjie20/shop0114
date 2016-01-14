<?php
namespace Admin\Model;


use Think\Model;

class SupplierModel extends BaseModel
{
    // 每个表单都有自己的验证规则
    protected $_validate = array(
        array('name','require','名字不能够为空'),
        array('name','','名字已重复!请更改!','','unique'),
        array('intro','require','描述不能够为空'),
    );
}