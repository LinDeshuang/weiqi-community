<?php
use think\Config;
//邮箱相关配置
return 
 [
 	'MAIL_HOST' =>'smtp.qq.com',//smtp服务器的名称
	'MAIL_SMTPAUTH' =>TRUE, //启用smtp认证
	'MAIL_USERNAME' =>'1287702249@qq.com',//发件人的邮箱名
	'MAIL_PASSWORD' =>'nyfycnndgmnjbaaa',//163邮箱发件人授权密码
	'MAIL_FROM' =>'1287702249@qq.com',//发件人邮箱地址
	'MAIL_FROMNAME'=>'围棋打谱系统',//发件人姓名
	'MAIL_CHARSET' =>'utf-8',//设置邮件编码
	'MAIL_ISHTML' =>TRUE, // 是否HTML格式邮件
  ];