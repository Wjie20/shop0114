<?php
/**
 * Created by PhpStorm.
 * User: wangjie
 * Date: 2016/1/19
 * Time: 23:19
 */

namespace Home\Controller;


use Think\Controller;

class MemberController extends Controller
{
    /**
     * 用户注册
     */
    public function register()
    {
        if (IS_POST) {
            //用户注册时,先对比手机验证码是否正确,如果不正确,就不需要再查询数据库.直接提示错误,终止执行
            $tel = I('post.tel');
            //redis中保存的验证码
            $redis_smsCode = S($tel);
            //用户输入的验证码
            $code = I('post.smsCode');
            if ($redis_smsCode != $code) {
                $this->error('验证码不正确!');
            }
            //如果验证码正确,再将数据保存到数据库中.
            $memberModel = D('Member');
            if ($memberModel->create() !== false) {
                if ($memberModel->add() !== false) {
                    $this->success('注册成功!', U('login'));
                    return;
                }
            }
            $this->error(show_model_error($memberModel));;
        } else {
            $this->display('register');
        }
    }


    /**
     * 用户登录
     */
    public function login()
    {
        if (IS_POST) {
            $memberModel = D('Member');
            if ($memberModel->create() !== false) {
                $userInfo = $memberModel->checkLogin();
                if (is_array($userInfo)) {
                    //登录成功,将用户信息保存在session中
                    login($userInfo);
                    //由于定义当前登录用户id的常量在行为类中.方法执行到这里的时候UID常量还没有被定义,所以需要重新定义
                    defined('UID') or define('UID', $userInfo['id']);
                    //将用户在cookie中保存的商品信息添加到数据库中,并清空cookie
                    $shoppingCarModel = D('ShoppingCar');
                    $shoppingCarModel->cookie2db();
                    $this->success('登录成功!', U('Index/index'));
                    return;
                }
            }
            $this->error('登录失败!' . show_model_error($memberModel));
        } else {
            $this->display('login');
        }
    }

    /**
     * 根据注册用户的手机号,获取手机验证码
     * @param $tel
     */
    public function getSmsCode($tel)
    {
        //生成随机的短信验证码
        $smsCode = \Org\Util\String::randNumber(1000, 9999);
        //将手机验证码存入redis中,当前手机号作为键.用作后面的验证
        S($tel, $smsCode, 60);
        //发送验证信息
        sendSMS('注册验证', '{"code":"' . $smsCode . '","product":"京西商城"}', $tel, 'SMS_4730837');
    }

    /**
     * 根据用户的手机号和输入的的验证和redis中保存的验证码的对比结果,先在前台对用户输入手机验证码进行验证.
     * 返回浏览器所需的json格式的Boolean值
     * @param $smsCode
     * @param $tel
     */
    public function checkTelCode($smsCode, $tel)
    {
        $redisCode = S($tel);
        $result = $redisCode == $smsCode;
        $this->ajaxReturn($result);
    }

    /**
     *  根据用户输入的用户名 | 电话 | 邮箱 到数据库中进行验证,是否已经存在当前数据,
     *  并返回json格式的Boolean值,用于显示提示信息
     */
    public function checkIsExists()
    {
        $param = I('get.');
        $memberModel = D('Member');
        $result = $memberModel->check($param);
        $this->ajaxReturn($result);
    }

    /**
     * 激活用户账号
     * @param $id
     * @param $email
     */
    public function activate($id, $email)
    {
        $memberModel = D('Member');
        $result = $memberModel->activate($id, $email);
        if ($result === false) {
            $this->error('账户激活出错!', U('register'));
        } else {
            $this->success('激活成功!', U('login'));
        }
    }


    public function logOut()
    {
        session('user_info', null);
        $this->success('已注销', U('Index/index'));
    }

}