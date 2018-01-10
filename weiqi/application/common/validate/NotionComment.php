<?php
namespace app\common\validate;

use think\Validate;

Class NotionComment extends Validate
{
	protected $rule = [
					'comment_content' 		    => 'require|length:1,200'
				];

	protected $message = [
					'comment_content.require'   => '{"name":"comment_content","msg":"评论内容不能为空"}',
					'comment_content.length'    => '{"name":"comment_content","msg":"评论内容长度不能大于200"}'
				];
}