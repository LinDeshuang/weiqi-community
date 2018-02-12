<?php
namespace app\common\model;

use think\Model;

Class NotionComment extends Model
{
	protected $autoWriteTimestamp = true;
	protected $auto = [];
	protected $insert = ['comment_status'=>1];
	protected $update = [];

	//处理评论内容
    protected function setCommentContentAttr($value)
    {
        return base64_encode($value);
    }

}