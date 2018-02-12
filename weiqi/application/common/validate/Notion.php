<?php
namespace app\common\validate;

use think\Validate;

Class Notion extends Validate
{
	protected $rule = [
					'user_id' 		    => 'require',
					'notion_content' 	=> 'require|length:1,300'
				];

	protected $message = [
					'user_id.require' 		=> '{"name":"user_id","msg":"用户登录已失效，请重新登录"}',
					'notion_content.require'   => '{"name":"notion_content","msg":"发表内容不能为空"}',
					'notion_content.length'    => '{"name":"notion_content","msg":"发表内容长度不能大于300"}'
				];
}