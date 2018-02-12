<?php
namespace app\common\model;

use think\Model;
use traits\model\SoftDelete;

Class Notion extends Model
{
	use SoftDelete;
	protected $deleteTime = 'delete_time';
	protected $autoWriteTimestamp = true;
	protected $auto = [];
	protected $insert = ['notion_status'=>1,'praise_count'=>0,'comment_count'=>0];
	protected $update = [];

	//处理介绍字段
    protected function setNotionContentAttr($value)
    {
        return htmlspecialchars_decode($value);
    }

}