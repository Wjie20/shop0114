<?php
/**
 * Created by PhpStorm.
 * User: wangjie
 * Date: 2016/1/22
 * Time: 17:52
 */

namespace Home\Model;


use Think\Model;

class ShoppingCarModel extends Model
{

    /**
     *  判断用户点击加入购物车时,是否是登录状态.
     * 如果没有登录,将加入购物车的数据保存在cookie中
     * 如果已经登录,保存到数据库
     * @param mixed|string $shoppingCar
     */
    public function add($requestData)
    {
        if (!isLogin()) {
            return $this->addCookie($requestData);
        } else {
            return $this->addDB($requestData);
        }

    }

    /**
     * 将购物车数据保存到shopping_car表中
     * @param $requestData
     * @return xxx
     */
    public function addDB($requestData)
    {

        //先查询数据库中有没有当前加入购物车的商品对应的购物车明细记录.如果有,修改该数据的num字段上的值.如果没有,新添加一天记录
        $row = $this->where(array('member_id' => UID, 'goods_id' => $requestData['id']))->find();
        if ($row) {
            return $this->where(array('goods_id' => $requestData['id'], 'member_id' => UID))->setInc('num', $requestData['num']);
        } else {
            $data = array('member_id' => UID, 'goods_id' => $requestData['id'], 'num' => $requestData['num']);
            return parent::add($data);
        }
    }


    /**
     * 将购物车数据保存到cookie中
     * @param $requestData
     */
    private function addCookie($requestData)
    {
        //先试着从cookie中取出shopping_car保存的数据
        $shoppingCar = cookie('shopping_car');
        //如果没有保存相关数据,创建一个空数组准备存放购物车数据
        if (empty($shoppingCar)) {
            $shoppingCar = array();
        } else {   //如果有保存,将其反序列化
            $shoppingCar = unserialize($shoppingCar);
        }
        //cookie中保存的商品数据是以二维数组的形式存在,每个小数组代表一条商品数据
        $flag = false;  //用于记录当前商品是否在cookie中存在的标记


        foreach ($shoppingCar as &$item) {
            if ($item['id'] == $requestData['id']) {
                $item['num'] += $requestData['num'];
                $flag = true;
                break;
            }
        }
        unset($item);
        //如果当前商品之前没有网cookie中保存过,在添加一跳新的数据到shopping_car中
        if ($flag === false) {
            $shoppingCar[] = $requestData;
        }

        //将准备好的购物车明细序列化后保存到cookie中30天
        cookie('shopping_car', serialize($shoppingCar), 2592000);

    }

    /**
     *  获取购物车列表页所需的数据
     *  1. 用户没有登录的情况下,从cookie中获取
     *  2. 用户登录了的情况下,从数据库中获取
     */
    public function getList()
    {
        if (!isLogin()) {
            $shoppingCar = cookie('shopping_car');
            if (!empty($shoppingCar)) {
                $shoppingCar = unserialize($shoppingCar);
                $goodsModel = D('Goods');
                foreach ($shoppingCar as &$item) {
                    $row = $goodsModel->field('id,name,logo,shop_price')->find($item['id']);
                    $item['logo'] = $row['logo'];
                    $item['name'] = $row['name'];
                    $item['price'] = $row['shop_price'];
                }
                unset($item);
                return $shoppingCar;
            }
            return false;
        } else {
            $this->alias('obj');
            $this->field('g.id,g.name,g.logo,g.shop_price as price,obj.num');
            $this->join('__GOODS__ as g on obj.goods_id=g.id');
            $this->where(array('member_id' => UID));
            $rows = $this->select();
            return $rows;
        }
    }


    /**
     *  当用户登录成功之后,将用户保存在cookie中的购物车数据保存到数据库,并清空cookie中的数据
     */
    public function cookie2db()
    {
        $shoppingCar = cookie('shopping_car');
        if (!empty($shoppingCar)) {
            $shoppingCar = unserialize($shoppingCar);
            foreach ($shoppingCar as $item) {
                $this->addDB($item);
            }
        }

        cookie('shopping_car', null);
    }

}