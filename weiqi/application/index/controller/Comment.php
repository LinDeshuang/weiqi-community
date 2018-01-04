<?php
namespace app\index\controller;

use think\Controller;
use think\Db;
use think\Config;
use think\Session;
use app\common\model\ManualComment as manualComment_model;
use app\common\validate\ManualComment as manualComment_validate;

class Comment extends Controller
{
    protected $user_id;
    protected $manualComment_model;
    protected $manualComment_validate;
    protected function _initialize()
    {
        parent::_initialize();
        $this->user_id = Session::get('user_id');
        $this->manualComment_model = new manualComment_model();
        $this->manualComment_validate = new manualComment_validate();
    }

    /**
    *棋谱评论
    */
    public function manualComment()
    {
        if($this->request->isPost())
        {
            if(!$this->user_id)
                {
                    exit(json_encode([
                        'errcode' => '40100',
                        'errmsg' => '尚未登录或登录已失效，评论失败'
                        ],JSON_UNESCAPED_UNICODE));
                }
            $data = $this->request->post();
            $data['user_id'] = $this->user_id;
            if(!$this->manualComment_validate->check($data))
            {
                $errMsg  = json_decode($this->manualComment_validate->getError(),true);
                $errCode = '40200';
            }
            else
            {
                if(!$this->manualComment_model->save($data))
                {
                    $errMsg = '评论失败，网络错误';
                    $errCode = '40100';
                }
                else
                {
                    Db::name('weiqi_manual')->where(['id'=>$data['manual_id']])->setInc('comment_count');
                    $errMsg = '评论成功';
                    $errCode = 0;
                }
            }
            exit(json_encode([
                        'errcode' => $errCode,
                        'errmsg' =>  $errMsg
                        ],JSON_UNESCAPED_UNICODE));
        }
    }
}
