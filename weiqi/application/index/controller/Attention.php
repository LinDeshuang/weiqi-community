<?php
namespace app\index\controller;

use think\Controller;
use think\Db;
use think\Config;
use think\Session;
use app\common\model\AttentionList as attentionList_model;
use app\common\validate\AttentionList as attentionList_validate;

class Attention extends Controller
{
    protected $user_id;
    protected $attentionList_model;
    protected $attentionList_validate;
    protected function _initialize()
    {
        parent::_initialize();
        $this->user_id = Session::get('user_id');
        $this->attentionList_model = new attentionList_model();
        $this->attentionList_validate = new attentionList_validate();
    }

    public function attention()
    {
      if($this->request->isPost())
      {
        if(!$this->user_id)
        {
            exit(json_encode([
                'errcode' => '40100',
                'errmsg' => '操作失败，尚未登录或登录已失效，<a href="/admin/login/index">前往登录</a>'
                ],JSON_UNESCAPED_UNICODE));
        }
        $data = $this->request->post();
        $data['user_id'] = $this->user_id;
        switch ($data['attention_type'])
        {
            case 1:
              if($this->attentionList_model->where(['user_id'=>$this->user_id,'attention_id'=>$data['attention_id']])->find())
              {
                exit(json_encode([
                    'errcode' => '40100',
                    'errmsg' => '操作失败，已经关注过了'
                    ],JSON_UNESCAPED_UNICODE));
              }
              if(!$this->attentionList_validate->check($data))
              {
                $errMsg = json_decode($this->attentionList_validate->getError(),true);
                $errCode = '40200';
              }
              else
              {
                if(!$this->attentionList_model->allowField(true)->save($data))
                {
                  $errMsg = '关注失败，参数错误';
                  $errCode = '40100';
                }
                else
                {
                  $errMsg = '关注成功';
                  $errCode = 0;
                }
              }
            break;
          case 0:
              if($this->attentionList_model->where(['user_id'=>$this->user_id,'attention_id'=>$data['attention_id']])->delete())
              {
                $errMsg = '取消关注成功';
                $errCode = 0;
              }
              else
              {
                $errMsg = '取消关注失败，参数错误';
                $errCode = '40100';
              }
            break;

          default:
            $errMsg = '参数错误，操作失败';
            $errCode = '40100';
            break;
        }
        exit(json_encode([
                    'errcode' => $errCode,
                    'errmsg' =>  $errMsg
                    ],JSON_UNESCAPED_UNICODE));
      }
    }

}
