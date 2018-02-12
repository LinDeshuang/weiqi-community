<?php
namespace app\common\validate;

use think\Validate;

Class WeiqiManual extends Validate
{
	protected $rule = [
					'user_id' 		=> 'require',
					'manual_name' 	=> 'require|length:1,20',
					'manual_intro'  => 'length:5,250',
					'manual_data' 	=> 'require'
				];

	protected $message = [
					'user_id.require' 		=> '{"name":"user_id","msg":"用户登录已失效，请重新登录"}',
					'manual_name.require'   => '{"name":"manual_name","msg":"棋谱名不能为空"}',
					'manual_name.length'    => '{"name":"manual_name","msg":"棋谱名长度不能大于20"}',
					'manual_intro.length'   => '{"name":"manual_intro","msg":"棋谱简介长度不能小于5,大于250"}',
					'manual_data.require'   => '{"name":"manual_data","msg":"棋谱数据出错，请刷新页面重试"}'
				];
}