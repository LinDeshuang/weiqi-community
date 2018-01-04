<?php
namespace app\admin\controller;

use think\Controller;
use think\Session;
use think\Config;
use think\Loader;
use app\common\controller\AdminBase;
use app\common\validate\Notion as notion_validate;
use app\common\model\Notion as notion_model;
/**
*我的观点控制器
*/
class Notion extends AdminBase
{
	protected $notion_model;
	protected $notion_validate;
	protected $user_id;
	protected function _initialize()
	{
		parent::_initialize();
		$this->notion_model = new notion_model();
		$this->notion_validate = new notion_validate();
		$this->user_id = Session::get('user_id');
	}
/**
*管理观点首页
*/
    public function index()
    {
    	$notion = $this->notion_model->where(['status'=>1,'user_id'=>$this->user_id])->order('create_time','desc')->paginate(8);
    	$page = $notion->render();
    	$this->assign('user_id',$this->user_id);
    	$this->assign('notion',$notion);
    	$this->assign('page',$page);
    	return $this->fetch();
    }

/**
*发表观点
*/
	public function giveOut()
	{
    	if($this->request->isPost())
    	{
    		$data = $this->request->post();
    		if(!$this->notion_validate->check($data))
    		{
    			$errMsg  = json_decode($this->notion_validate->getError(),true);
    			$errCode = '40200';
    		}
    		else
    		{
    			$res = $this->notion_model->save($data);
    			if($res)
    			{
    				$errMsg = '发表成功';
    				$errCode = 0;
    			}
    			else
    			{
    				$errMsg = '发表失败';
    				$errCode = '40100';
    			}
    		}
    				exit(json_encode([
    							'errcode' => $errCode,
    							'errmsg' => $errMsg
    						],JSON_UNESCAPED_UNICODE));
    	}
	}

/**
*删除
*/
	public function delNotion($id)
	{
        if($this->notion_model->destroy($id))
        {
            $this->success('删除成功');
        }
        else
        {
            $this->error('删除失败');

        }
	}
}
