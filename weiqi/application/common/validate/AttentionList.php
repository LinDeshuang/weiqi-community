<?php
namespace app\common\validate;

use think\Validate;

Class AttentionList extends Validate
{
	protected $rule = [
					'user_id' 		    => 'require',
          'attention_id'    => 'require'
				];

	protected $message = [
					'user_id.require'   => '{"name":"user_id","msg":"用户id缺失"}',
					'attention_id.require'    => '{"name":"attention_id","msg":"被关注者的id缺失"}'
				];
}
