<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/9
 * Time: 11:55
 */

namespace Admin\Model;


use Think\Model;
use Think\Page;

class BaseModel extends Model
{
    //开启批量验证
    protected $patchValidate = true;

    /**
     * 得到分页列表中的数据
     * $pageResult = >array(
     * 'rows'=>二维数组.   分页列表数据
     * 'pageHtml'=>分页工具条的html.
     * )
     */
    public function getPageResult($wheres = array())
    {  //有默认的条件, 在调用该方法是不需要传入.
        //因为总条数和分页列表都需要查询出状态>-1的数据
        $wheres['obj.status'] = array('gt', -1);


        //>>1.准备分页工具条html
        $pageHtml = '';
        $pageSize = 5;  //每页多少条
        $this->alias('obj');
        $totalRows = $this->where($wheres)->count();  //总条数
        $page = new Page($totalRows, $pageSize);
        $pageHtml = $page->show();//生成分页的html

        //>>2.准备分页列表数据
        //$page->firstRow 起始条数    (页码-1)*每页数
        if ($page->firstRow > $totalRows) {  //起始条数大于总条数, 显示最后一页数据.
            $page->firstRow = $totalRows - $page->listRows;  //起始条数= 总条数-每页多少条
        }
        $this->alias('obj');
        $this->_setModel();
        $rows = $this->where($wheres)->limit($page->firstRow, $page->listRows)->select();
        $this->_handleRows($rows);
        return array('rows' => $rows, 'pageHtml' => $pageHtml);
    }

    /**
     * 查询出状态大于-1
     */
    public function getList($field = '*', $wheres = array())
    {
        $wheres['status'] = array('gt', -1);
        return $this->field($field)->where($wheres)->select();
    }

    /**
     * 改变id修改其状态为status
     * @param $id
     * @param $status   默认值为-1表示删除
     */
    public function changeStatus($id, $status = -1)
    {
        $data = array('id' => array('in', $id), 'status' => $status);
        if ($status == -1) {
            $data['name'] = array('exp', "concat(name,'_del')");  //update supplier set name = concat(name,'_del'),status = -1    where id in (1,2,3);
        }
        return parent::save($data);
    }

    /**
     * 钩子方法,主要用于钩子类覆盖
     */
    protected function _setModel()
    {

    }

    protected function _handleRows(&$rows){
        foreach($rows as &$row){
            $row['is_best'] =( $row['goods_status'] & 1);
            $row['is_new'] =(( $row['goods_status'] & 2 ) >> 1);
            $row['is_hot'] =(( $row['goods_status'] & 4) >> 2);
        }
        unset($row);
    }
}