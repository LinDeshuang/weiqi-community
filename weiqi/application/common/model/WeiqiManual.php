<?php
namespace app\common\model;

use think\Model;
use traits\model\SoftDelete;

Class WeiqiManual extends Model
{
	use SoftDelete;
	protected $deleteTime = 'delete_time';
	protected $autoWriteTimestamp = true;
	protected $auto = [];
	protected $insert = ['pwd','weiqi_intro','status'=>0,'praise_count'=>0,'comment_count'=>0,'click_count'=>0];
	protected $update = [];

	//处理介绍字段
    protected function setManualIntroAttr($value)
    {
        return htmlspecialchars_decode($value);
    }

}