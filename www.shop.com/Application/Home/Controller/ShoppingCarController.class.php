<?php
/**
 * Created by PhpStorm.
 * User: wangjie
 * Date: 2016/1/22
 * Time: 12:48
 */

namespace Home\Controller;


use Think\Controller;

class ShoppingCarController extends Controller
{
    /**
     *  购物车列表页面展示
     */
    public function index()
    {
        //在展示之前,先取出当前用户加入购物车的商品信息,并分配到页面用作数据列表的展示
        $shoppingCarModel = D('ShoppingCar');
        $rows = $shoppingCarModel->getList();
        $this->assign('rows', $rows);
        $this->display('index');
    }

    /**
     *  添加商品到购物车.
     */
    public function add()
    {
        $shoppingCarModel = D('ShoppingCar');
        $result = $shoppingCarModel->add(I('post.'));
//        exit;
        if ($result === false) {
            $this->error('添加失败!' . show_model_error($shoppingCarModel));
        } else {
            $this->success('添加成功!', U('index'));
        }

    }
}