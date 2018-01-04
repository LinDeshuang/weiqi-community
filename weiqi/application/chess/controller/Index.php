<?php
namespace app\chess\controller;

use think\Controller;
use think\Request;
use think\Db;
use think\Session;
use app\common\controller\AdminBase;
use app\common\model\WeiqiManual as weiqi_manual_model;
use app\common\validate\WeiqiManual as weiqi_manual_validate;

class Index extends AdminBase
{
	protected $user_id;
    protected $weiqi_manual_model;
    protected $weiqi_manual_validate;
	protected function _initialize()
	{
		parent::_initialize();
        $this->user_id = Session::get('user_id');
        $this->weiqi_manual_model = new weiqi_manual_model();
        $this->weiqi_manual_validate = new weiqi_manual_validate();
	}
    public function index()
    {
        $this->assign('user_id' , $this->user_id);
        return $this->fetch();

    }
    /**
    *保存数据
    */
    public function save()
    {
        if($this->request->isPost())
        {
            $data = $this->request->post();
            if(!$this->weiqi_manual_validate->check($data))
            {
                $errMsg  = json_decode($this->weiqi_manual_validate->getError(),true);
                $errCode = '40200';
            }
            else
            {
                if(isset($data['id']))
                {
                    $id = $data['id'];
                    unset($data['id']);
                    $res = $this->weiqi_manual_model->allowField(true)->save($data , ['id'=>$id]);
                }
                else
                {
                    $res = $this->weiqi_manual_model->allowField(true)->save($data);
                }
                
                if($res)
                {
                    $errMsg = '保存成功';
                    $errCode = 0;
                }
                else
                {
                    $errMsg = '保存失败';
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
    *管理棋谱
    */
    public function manualManage()
    {
        $manual = $this->weiqi_manual_model->where(['user_id' => $this->user_id])->order('create_time', 'desc')->paginate(5);
        $page = $manual->render();
        $this->assign('manual' , $manual);
        $this->assign('page' , $page);
        return $this->fetch();
    }
    /**
    *编辑数据
    */
    public function editManual($id)
    {
        $manual = $this->weiqi_manual_model->where(['user_id' => $this->user_id , 'id' => $id ])->find();
        $this->assign('manual' , $manual);
        $this->assign('user_id' , $this->user_id);
        return $this->fetch();
    }
    /**
    *删除数据
    */
    public function delManual($id)
    {
        if($this->weiqi_manual_model->destroy($id))
        {
            $this->success('删除成功');
        }
        else
        {
            $this->error('删除失败');

        }
    }
    /**
    *分享棋谱
    */
    public function shareManual($id , $isShare)
    {
        if($isShare)
        {
            $update = ['manual_status'=>1 , 'share_time'=>time()];
            $successMsg = '分享成功';
            $erroMsg = '分享失败';
        }
        else
        {
            $update = ['manual_status'=>0];
            $successMsg = '取消分享成功';
            $erroMsg = '取消分享失败';
        }
        if($this->weiqi_manual_model->save($update,['id'=>$id]))
        {
            $this->success($successMsg);
        }
        else
        {
            $this->error($erroMsg);
        }
    }
    /**
    *演示棋谱
    */
    public function playManual($id)
    {
        $manual = $this->weiqi_manual_model->where(['user_id' => $this->user_id , 'id' => $id ])->find();
        $this->assign('manual' , $manual);
        $this->assign('user_id' , $this->user_id);
        return $this->fetch();
    }
}
