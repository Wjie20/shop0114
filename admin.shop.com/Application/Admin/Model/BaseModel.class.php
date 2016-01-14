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
        $wheres['status'] = array('gt', -1);


        //>>1.准备分页工具条html
        $pageHtml = '';
        $pageSize = 2;  //每页多少条
        $totalRows = $this->where($wheres)->count();  //总条数
        $page = new Page($totalRows, $pageSize);
        $pageHtml = $page->show();//生成分页的html

        //>>2.准备分页列表数据
        //$page->firstRow 起始条数    (页码-1)*每页数
        if ($page->firstRow > $totalRows) {  //起始条数大于总条数, 显示最后一页数据.
            $page->firstRow = $totalRows - $page->listRows;  //起始条数= 总条数-每页多少条
        }
        $row = $this->where($wheres)->limit($page->firstRow, $page->listRows)->select();
        return array('rows' => $row, 'pageHtml' => $pageHtml);
    }

    /**
     * 查询出状态大于-1
     */
    public function getList()
    {
        return $this->where(array('status' => array('gt', -1)))->select();
    }

    /**
     * 改变id修改其状态为status
     * @param $id
     * @param $status   默认值为-1表示删除
     */
    public function changeStatus($id, $status = -1)
    {
        /**
         * if($id是数组){
         * 'id'=>array('in',array(1,2,3))
         * }else{
         * 'id'=>array('in',"1")
         * }
         */
        $data = array('id' => array('in', $id), 'status' => $status);
        if ($status == -1) {
            $data['name'] = array('exp', "concat(name,'_del')");  //update supplier set name = concat(name,'_del'),status = -1    where id in (1,2,3);
        }
        return parent::save($data);
    }
}