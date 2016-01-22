<?php

/**
 * 1. 需要126的账号和授权密码   18758546284@163.com   wj759445818
 * 2. 需要126服务器的IP地址(域名)   smtp.163.com
 */
require './PHPMailer/PHPMailerAutoload.php';

$mail = new PHPMailer;

//$mail->SMTPDebug = 3;                               // Enable verbose debug output

$mail->isSMTP();                                      // Set mailer to use SMTP
$mail->Host = 'smtp.163.com';                         // Specify main and backup SMTP servers
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->Username = '18758546284@163.com';                 // SMTP username
$mail->Password = 'wj759445818';                           // SMTP password
//$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
//$mail->Port = 587;                                    // TCP port to connect to
//设置编码
$mail->CharSet = 'utf-8';

$mail->setFrom('18758546284@163.com', '发件人是我');       //发件人
$mail->addAddress('18758546284@163.com', '收件人还是我');     // Add a recipient 收件人
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
$mail->Subject = '这是激活邮件 oye!';
//邮件内容
$mail->Body    = '<b>哈哈</b><br><a href="http://www.baidu.com">点击激活!</a>';
//当前内容中的html无效时,要发送的内容
$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

if(!$mail->send()) {
    echo 'Message could not be sent...';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo 'Message has been sent !';
}
