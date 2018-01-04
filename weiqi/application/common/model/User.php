<?php
namespace app\common\model;

use think\Model;
use traits\model\SoftDelete;

Class User extends Model
{
	use SoftDelete;
	protected $deleteTime = 'delete_time';
	protected $autoWriteTimestamp = true;
	protected $auto = [];
	protected $insert = ['pwd','introduction','status'=>1,'photo'=>'/static/images/default.png','points'=>0];
	protected $update = [];

	//处理介绍字段
    protected function setIntroductionAttr($value)
    {
        return htmlspecialchars_decode($value);
    }

}