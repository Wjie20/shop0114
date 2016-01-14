<?php

namespace Admin\Model;


use Think\Model;
use Think\Page;

class GoodsTypeModel extends BaseModel
{
    // 自动验证定义
    protected $_validate = array(
                array("name",'require', "名称不能为空"),
        array("sort",'require', "排序不能为空"),
        array("status",'require', "状态不能为空"),
    );

}