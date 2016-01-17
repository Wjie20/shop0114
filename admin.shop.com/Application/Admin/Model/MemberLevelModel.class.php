<?php
namespace Admin\Model;


use Think\Model;

class MemberLevelModel extends BaseModel
{
    // 每个表单都有自己的验证规则
    protected $_validate = array(
        array('name','require','会员级别名称不能够为空'),
array('low','require','最低积分不能够为空'),
array('high','require','最高积分不能够为空'),
array('discount','require','折扣不能够为空'),
array('status','require','状态不能够为空'),


    );
}