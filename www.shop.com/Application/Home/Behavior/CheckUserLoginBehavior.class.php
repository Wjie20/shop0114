<?php
/**
 * Created by PhpStorm.
 * User: wangjie
 * Date: 2016/1/22
 * Time: 19:16
 */

namespace Home\Behavior;


use Think\Behavior;

class CheckUserLoginBehavior extends Behavior
{
    /**
     * 执行行为 run方法是Behavior唯一的接口
     * @access public
     * @param mixed $params 行为参数
     * @return void
     */
    public function run(&$params)
    {
        if (isLogin()) {
            $user_info = login();
            defined('UID') or define('UID', $user_info['id']);
        }
    }

}