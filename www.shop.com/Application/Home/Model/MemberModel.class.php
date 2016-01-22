<?php
/**
 * Created by PhpStorm.
 * User: wangjie
 * Date: 2016/1/21
 * Time: 20:20
 */

namespace Home\Model;


use Think\Model;

class MemberModel extends Model
{
    /**
     * 自动完成:
     *  1. 生成随机位的盐
     *  2. 生成注册时间,就是当前时间
     *  3. 获取注册用户的IP,由于数据库中保存IP字段的类型是bigint,所以养将当前IP通过iP2long函数进行转换
     * @var array
     */
    protected $_auto = array(
        array('salt', '\Org\Util\String::randString', '', 'function'),
        array('regtime', NOW_TIME),
        array('reg_ip', 'get_client_ip_2long', '', 'callback'),
    );

    /**
     * IP转换
     * @return int
     */
    public function get_client_ip_2long()
    {
        $client_ip = get_client_ip();
        return ip2long($client_ip);
    }

    /**
     * 添加用户
     */
    public function add()
    {
        $email = $this->data['email'];
        //对用用户输入的密码进行加盐加密
        $this->data['password'] = md5(md5($this->data['password']) . $this->data['salt']);
        $id = parent::add();
        $activateKey = md5($email);
        //给用户发送激活邮件,传递当前注册用户的id和md5加密后的用户email,当用户点击链接,请求MemberController中activate的方法,对用户账户进行验证激活
        $mail_content = " 请点击下面链接激活京西商城会员账号: <br/><br/><a href='http://www.shop.com/Member/activate/id/{$id}/email/{$activateKey}' target='_blank'> 感谢您的支持! 请使劲点我! </a>";
        sendMail($email, '京西商城激活邮件', $mail_content);
        return $id;
    }

    public function check($param)
    {
        //传递过来要验证的数据是一个键值对应的一维数组,例: array('tel'=>18758546284)
        //取出需要到数据库中进行验证的键名
        $key = array_keys($param);
        $key = $key[0];
        //查询状态为1,并且对应字段的值和参数中的值相等的数据
        $count = $this->where(array('status' => 1, $key => $param[$key]))->count();
        //如果有就返回false.没有就是true
        return $count == 0;

    }

    /**
     * 根据激活邮件传递的数据,判断,激活
     * @param $id
     * @param $key
     * @return bool
     */
    public function activate($id, $key)
    {
        //根据激活邮件传递的用户id查询出当前id对应在数据库中的email
        $email = $this->getFieldById($id, 'email');
        //如果数据库中的email加密后等于传递的email,激活成功,改变当前用户在数据库中的状态为1
        if (md5($email) == $key) {
           return $this->save(array('status' => 1, 'id' => $id));
        } else {
            return false;
        }
    }

    /**
     *  验证登录
     * @return bool | array
     */
    public function checkLogin()
    {
        $username = $this->data['username'];
        $password = $this->data['password'];
        if (empty($username)) {
            $this->error = '请输入用户名!';
            return false;
        }
        if (empty($password)) {
            $this->error = '请输入密码!';
            return false;
        }
        //更具当前输入的用户名,查询数据库中对应的数据
        $user_info = $this->field('id,username,password,salt,status')->where(array('status' => array('gt', -1)))->getByUsername($username);
        if (empty($user_info)) {
            $this->error = '用户名不存在!';
            return false;
        }
        if ($user_info['status'] == 0) {
            $this->error = '您的账号还未激活,或被锁定.请注意查收激活邮件!';
            return false;
        }
        //对比密码.如果对比成功,将当前用户对应数据的最后登录时间和最后登录IP字段的值修改
        if (md5(md5($password) . $user_info['salt']) == $user_info['password']) {
            $result = parent::save(array('last_login_time' => NOW_TIME, 'last_login_ip' => ip2long(get_client_ip()), 'id' => $user_info['id']));
            if ($result === false) {
                $this->error = '系统繁忙,请稍后再试!';
                return false;
            }
            return $user_info;
        } else {
            $this->error = '密码不正确!';
            return false;
        }
    }
}