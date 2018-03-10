<?php
namespace app\admin\controller;

use think\Controller;
use think\Session;
use think\Config;

/**
* 邮箱验证码生成器
*/
class Mailer extends Controller
{
	protected function _initialize()
	{
		parent::_initialize();
	}

    public function index()
    {
    	if($this->request->isPost()){
    		$data = $this->request->post();
    		if(empty($data['email'])){
    			$errCode = '40200';
    			$errMsg = '邮箱不能为空';
    		}else if(!preg_match("/^[a-z0-9]+([._\\-]*[a-z0-9])*@([a-z0-9]+[-a-z0-9]*[a-z0-9]+.){1,63}[a-z0-9]+$/i", $data['email'])){
    			$errCode = '40200';
    			$errMsg = '邮箱格式错误';
    		}else{
    			$email = $data['email'];
				$mail = new \PHPMailer\PHPMailer\PHPMailer(); //实例化
		 
				//使用smtp鉴权方式发送邮件，当然你可以选择pop方式 sendmail方式等 本文不做详解
				//可以参考http://phpmailer.github.io/PHPMailer/当中的详细介绍
				$mail->isSMTP();
				//smtp需要鉴权 这个必须是true
				$mail->SMTPAuth=true;
				//链接qq域名邮箱的服务器地址
				$mail->Host = 'smtp.qq.com';
				//设置使用ssl加密方式登录鉴权
				$mail->SMTPSecure = 'tsl';
				//设置ssl连接smtp服务器的远程服务器端口号 可选465或587
				$mail->Port = 587;
				//设置smtp的helo消息头
				$mail->Helo = 'Hello smtp.qq.com Server';
				//设置发件人的主机域
				$mail->Hostname = 'weiqi.dayson.cc';
				//设置发送的邮件的编码 
				$mail->CharSet = 'UTF-8';
				//设置发件人姓名（昵称） 
				$mail->FromName = '来自围棋打谱系统';
				//smtp登录的账号
				$mail->Username ='1287702249@qq.com';
				//smtp登录的密码
				$mail->Password = 'nyfycnndgmnjbaaa';
				//设置发件人邮箱地址 这里填入上述提到的“发件人邮箱”
				$mail->From = '1287702249@qq.com';
				//邮件正文是否为html编码 注意此处是一个方法 不再是属性 true或false
				$mail->isHTML(true); 
				//设置收件人邮箱地址 该方法有两个参数 第一个参数为收件人邮箱地址 第二参数为给该地址设置的昵称 不同的邮箱系统会自动进行处理变动 这里第二个参数的意义不大
				$mail->addAddress($email,'用户');
				//添加该邮件的主题
				$mail->Subject = '邮箱验证码';
				//生成验证码 
				$code = rand(0,9).rand(0,9).rand(0,9).rand(0,9);
				//添加邮件正文 上方将isHTML设置成了true，则可以是完整的html字符串
				$mail->Body = "围棋打谱系统验证码：<b style=\"color:red;\">{$code}</b>";
				//为该邮件添加附件 该方法也有两个参数 第一个参数为附件存放的目录（相对目录、或绝对目录均可） 第二参数为在邮件附件中该附件的名称
				// $mail->addAttachment('./d.jpg','mm.jpg');
				//同样该方法可以多次调用 上传多个附件
				// $mail->addAttachment('./Jlib-1.1.0.js','Jlib.js');
				 
				 
				//发送命令 返回布尔值 
				//PS：经过测试，要是收件人不存在，若不出现错误依然返回true 也就是说在发送之前 自己需要些方法实现检测该邮箱是否真实有效
				if($mail->send()){
					Session::set($email,$code);
					$errCode = 0;
    				$errMsg = '发送成功';
				}else{
					$errCode = '40100';
    				$errMsg = '发送失败，请稍后再试';
				}
    		}
		   
			exit(json_encode([
    							'errcode' => $errCode,
    							'errmsg' => $errMsg
    						],JSON_UNESCAPED_UNICODE));
    	}else{
    		return "method error";
    	}
    }
}
