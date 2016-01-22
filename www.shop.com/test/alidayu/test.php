<?php

	include "TopSdk.php";
	date_default_timezone_set('Asia/Shanghai');

$c = new TopClient;
$c->appkey = '23302808';
$c->secretKey = '866a0f29b7c81e1c3c7eb4295dad8119';
$req = new AlibabaAliqinFcSmsNumSendRequest;
//$req->setExtend("123456");
$req->setSmsType("normal");
$req->setSmsFreeSignName("注册验证");
$req->setSmsParam('{"code":"哎呀我去~","product":"京西商城"}');
$req->setRecNum("18758546284");
$req->setSmsTemplateCode("SMS_4730837");
$resp = $c->execute($req);
?>