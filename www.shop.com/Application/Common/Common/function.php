<?php

/**
 * 获取model中的错误信息
 * @param $model
 * @return string  错误信息
 */
function show_model_error($model)
{
    //得到model中的错误信息
    $errors = $model->getError();
    $errorMsg = '<ul>';
    if (is_array($errors)) {
        //如果是数组将错误信息拼成一个ul
        foreach ($errors as $error) {
            $errorMsg .= "<li>{$error}</li>";
        }
    } else {
        $errorMsg .= "<li>{$errors}</li>";
    }
    $errorMsg .= '</ul>';
    return $errorMsg;
}


if (!function_exists('array_column')) {
    function array_column($params, $field)
    {
        $result = array();
        foreach ($params as $row) {
            $result [] = $row[$field];
        }
        return $result;
    }
}

/**
 * 自动生成下拉框
 * @param $name string 下拉框的名字
 * @param $rows array 循环的数据
 * @param string $select_option 需要选中的option的值
 * @param string $fieldValue 每一个option的value的值
 * @param string $fieldText 每一个option的文本内容的值
 */
function arr2select($name, $rows, $select_option = '', $fieldValue = 'id', $fieldText = 'name')
{
    $select_html = "<select name='{$name}'>";
    $select_html .= "<option value=''> - 请选择 - </option>";
    foreach ($rows as $row) {
        //如果传递的第三个参数等于当前行中的当前字段的值,选中当前option
        $selected = '';
        if ($row[$fieldValue] == $select_option) {
            $selected = 'selected';
        }
        $select_html .= "<option value='{$row[$fieldValue]}' {$selected} >{$row[$fieldText]}</option>";
    }
    $select_html .= '</select>';
    echo $select_html;
}


/**
 *  获取保存在session中的用户信息,根据参数判断,
 *  如果没有传递参数表示取出保存在session中的用户信息,
 * 如果有传递,表示将传递参数保存在session中
 * @param null $data
 * @return mixed
 */
function login($data = null)
{
    if ($data === null) {
        return session('user_info');
    } else {
        session('user_info', $data);
    }
}


function isLogin()
{
    return !(session('user_info') == null);
}

/**
 * 发送短信
 * @param $smsFreeSignName 短信签名
 * @param $smsParam  短信模板参数
 * @param $recNum    发送号码
 * @param $smsTemplateCode 短信模板编号
 */
function sendSMS($smsFreeSignName, $smsParam, $recNum, $smsTemplateCode)
{
    //加载vendor中的阿里大鱼
    vendor('Alidayu.TopSdk');
    date_default_timezone_set('Asia/Shanghai');
    $c = new TopClient;
    //获取配置数据
    $sms_config = C('SMS_CONFIG');
//    $c->appkey = '23302808';
//    $c->secretKey = '866a0f29b7c81e1c3c7eb4295dad8119';
    //APP_KEY
    $c->appkey = $sms_config['appkey'];
//    //安全码
    $c->secretKey = $sms_config['secretKey'];
    //创建发送短信的类对象
    $req = new AlibabaAliqinFcSmsNumSendRequest;
    //$req->setExtend("123456");
    //短信类型
    $req->setSmsType("normal");
    //短信签名
    $req->setSmsFreeSignName($smsFreeSignName);
    //为短信模板中的变量赋值,确定要发送什么样的短信内容
    $req->setSmsParam($smsParam);
    //发到哪里
    $req->setRecNum($recNum);
    //短信模板编号
    $req->setSmsTemplateCode($smsTemplateCode);
    //发射
    $resp = $c->execute($req);
}


function sendMail($address, $title, $content)
{

    /**
     * 1. 需要126的账号和授权密码   18758546284@163.com   wj759445818
     * 2. 需要126服务器的IP地址(域名)   smtp.163.com
     */
//    require './PHPMailer/PHPMailerAutoload.php';
    vendor('PHPMailer.PHPMailerAutoload');
    //创建发送邮件的对象
    $mail = new PHPMailer;

    //获取发送邮件的相关配置
    $mail_config = C('MAIL_CONFIG');
//$mail->SMTPDebug = 3;                               // Enable verbose debug output

    $mail->isSMTP();


    //邮件服务器地址                                // Set mailer to use SMTP
    $mail->Host = $mail_config['Host'];                         // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    //用户名
    $mail->Username = $mail_config['Username'];                 // SMTP username
    //授权密码
    $mail->Password = $mail_config['Password'];                           // SMTP password
    //是否加密??
    //$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
    //$mail->Port = 587;                                    // TCP port to connect to
    //设置编码
    $mail->CharSet = 'utf-8';

    $mail->setFrom($mail_config['From'], '发件人是我');       //发件人
    $mail->addAddress($address);     // Add a recipient 收件人
//$mail->addAddress('ellen@example.com');               // Name is optional
//$mail->addReplyTo('info@example.com', 'Information');
//抄送
//$mail->addCC('cc@example.com');
////密送
//$mail->addBCC('bcc@example.com');

//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
    $mail->isHTML(true);                                  // Set email format to HTML
//邮件标题
    $mail->Subject = $title;
//邮件内容
    $mail->Body = $content;
//当前内容中的html无效时,要发送的内容
    $mail->AltBody = $content;

    if (!$mail->send()) {
        echo '发送邮件失败!..';
        echo 'Error: ' . $mail->ErrorInfo;
    } else {
        echo '邮件已发送!';
    }

}

