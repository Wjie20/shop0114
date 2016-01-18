<?php
namespace Admin\Model;


use Admin\Service\NestedSetsService;
use Think\Model;

class GoodsCategoryModel extends BaseModel
{
    // 每个表单都有自己的验证规则
    protected $_validate = array(
        array('name', 'require', '名称不能够为空'),
        array('status', 'require', '状态不能够为空'),
        array('sort', 'require', '排序不能够为空'),
    );

    /**
     *
     * @return mixed
     */
    public function getTreeList($fields = '*', $flag = false)
    {
        //返回根据左边界排序并且状态值大于 -1的数据作为分类列表数据.
        $rows = $this->order('lft')->where(array('status' => array('gt', -1)))->select();
        if ($flag) {
            return json_encode($rows);
        } else {
            return $rows;
        }
    }

    /**
     * 添加分类,由于嵌套集合的特性,当添加一个新的分类的时候,如果不是在所有分类的最后添加,则添加之后,有许多分类的左右边界需要随之改变
     *  使用NestedSets插件,生成可以帮我们改变分类左右边界的sql语句,当改类不具备执行sql的能力,需要通过另一个借口的实现类对象来执行.@
     *  就使用到了DbMysqlInterfaceModel接口类.通过该接口的实现类,完成其中的接口方法..
     *  将实现了接口类方法的类对象传入嵌套集合类中,帮我们只想修改左右边界的sql语句.
     * @return false|int
     */
    public function add()
    {
        $dbMysql = new DbMysqlInterfaceImpModel();
        $nestedSetsService = new NestedSetsService($dbMysql, 'goods_category', 'lft', 'rght', 'parent_id', 'id', 'level');
        return $nestedSetsService->insert($this->data['parent_id'], $this->data, 'bottom');
    }

    public function save()
    {
        $dbMysql = new DbMysqlInterfaceImpModel();
        $nestedSetsService = new NestedSetsService($dbMysql, 'goods_category', 'lft', 'rght', 'parent_id', 'id', 'level');
        $nestedSetsService->moveUnder($this->data['id'], $this->data['parent_id']);
        return parent::save();
    }

    /**
     *  改变商品分类状态.当前分类如果有子分类,其子分类一并跟随改变
     *  使用连接查询的方式,将goods_category表看做两张表.
     *  使用分类id作为父表中的查询条件,查询出当前分类数据,再作为子表的查询条件,查询出当前分类的所有子分类,改变其状态
     * @param $id
     * @param int $status
     * @return bool
     */
    public function changeStatus($id, $status = -1)
    {
        //根据自己的id找到自己以及子孙节点的id
        $sql = "select child.id from  goods_category as child,goods_category as parent where  parent.id = {$id}  and child.lft>=parent.lft  and child.rght<=parent.rght";
        $rows = $this->query($sql);
        //arrar_column()方法只有PHP5.5之后才有.所以需要做兼容性处理.将改方法在公共function.php文件中判断系统有没有这个方法,没有就定义!
        $id = array_column($rows, 'id');
        //将查询的出的id转化为一个一维数组,再作为修改状态的条件.
        $data = array('id' => array('in', $id), 'status' => $status);
        if ($status == -1) {
            $data['name'] = array('exp', "concat(name,'_del')");  //update supplier set name = concat(name,'_del'),status = -1    where id in (1,2,3);
        }
        return parent::save($data);
    }

    public function getLeaf($goods_category_id)
    {
        $sql = "select child.id from goods_category as  parent,goods_category as child where  parent.id = {$goods_category_id} and child.lft>=parent.lft and child.rght<=parent.rght and child.lft+1 =child.rght";
        $rows = $this->query($sql);
        //从二维数组中得到id的值
        $ids = array_column($rows,'id');
        return $ids;
    }
}