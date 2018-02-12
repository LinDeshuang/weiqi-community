<?php
namespace app\common\validate;

use think\Validate;

Class User extends Validate
{
	protected $rule = [
					'account' 	  => 'require|length:8,30|alphaNum|unique:user',
					'pwd'	  	  => 'require|confirm:confirm_pwd|length:6,20',
					'name'    	  => 'require|length:1,20|unique:user',
					'gender'      => 'require',
					'level_type'  => 'require',
					'email'       => 'email',
					'verify_code' =>'require'
				];

	protected $message = [
					'account.require' => '{"name":"account","msg":"账号不能为空"}',
					'account.length'  => '{"name":"account","msg":"账号长度在8~30"}',
					'account.alphaNum'=> '{"name":"account","msg":"账号必须由字母和数字组成"}',
					'account.unique'  => '{"name":"account","msg":"账号已存在，请重新输入"}',
					'pwd.require'	  => '{"name":"pwd","msg":"密码不能为空"}',
					'pwd.length'	  => '{"name":"pwd","msg":"密码长度在6~20"}',
					'pwd.confirm'	  => '{"name":"pwd","msg":"两次输入的密码不一致请重新输入"}',
					'name.require'    => '{"name":"name","msg":"昵称不能为空"}',
					'name.length'     => '{"name":"name","msg":"昵称长度不能超过20"}',
					'name.unique'     => '{"name":"name","msg":"昵称已存在，请重新输入"}',
					'gender.require'  => '{"name":"gender","msg":"性别不能为空"}',
					'level_type.require'  => '{"name":"level_type","msg":"段位类型不能为空"}',
					'email.email'     => '{"name":"email","msg":"邮箱格式错误"}',
					'verify_code.require' => '{"name":"verify_code","msg":"验证码必填"}'
				];
}