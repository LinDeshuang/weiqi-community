<?php
namespace app\index\controller;

use think\Controller;
use think\Db;
use think\Config;
use think\Session;

class Praise extends Controller
{
    protected $user_id;
    protected function _initialize()
    {
        parent::_initialize();
        $this->user_id = Session::get('user_id');
    }
    /**
    *点赞棋谱
    */
    public function manualPraise()
    {
        if($this->request->isPost())
        {
            if(!$this->user_id)
                {
                    exit(json_encode([
                        'errcode' => '40100',
                        'errmsg' => '尚未登录，点赞失败'
                        ],JSON_UNESCAPED_UNICODE));
                }
            $data = $this->request->post();
            if(!Db::name('manualPraise')->where(['user_id'=>$this->user_id, 'manual_id'=>$data['manual_id']])->find())
                {
                    $data['user_id'] = $this->user_id;
                    if(Db::name('manualPraise')->insert($data) && Db::name('WeiqiManual')->where('id',$data['manual_id'])->setInc('praise_count') && Db::name('User')->where('id',$data['user_id'])->setInc('points'))
                        {
                            $errCode = 0;
                            $errMsg = '点赞成功';
                        }
                        else
                        {
                            $errCode = '40200';
                            $errMsg = '点赞失败';
                        }
                }
                else
                {
                    $errCode = '40200';
                    $errMsg = '您已经赞过了';
                }
                exit(json_encode([
                        'errcode' => $errCode,
                        'errmsg' => $errMsg
                    ],JSON_UNESCAPED_UNICODE));
        }
    }
    /**
    *点赞notion
    */
    public function notionPraise()
    {
        if($this->request->isPost())
        {
            if(!$this->user_id)
                {
                    exit(json_encode([
                        'errcode' => '40200',
                        'errmsg' => '尚未登录，点赞失败'
                        ],JSON_UNESCAPED_UNICODE));
                }
            $data = $this->request->post();
            if(!Db::name('notionPraise')->where(['user_id'=>$this->user_id, 'notion_id'=>$data['notion_id']])->find())
                {
                    $data['user_id'] = $this->user_id;
                    if(Db::name('notionPraise')->insert($data) && Db::name('notion')->where('id',$data['notion_id'])->setInc('praise_count'))
                        {
                            $errCode = 0;
                            $errMsg = '点赞成功';
                        }
                        else
                        {
                            $errCode = '40100';
                            $errMsg = '点赞失败';
                        }
                    
                }
                else
                {
                    $errCode = '40100';
                    $errMsg = '您已经赞过了';
                }
                exit(json_encode([
                        'errcode' => $errCode,
                        'errmsg' => $errMsg,
                        'eq'    => $data['eq']
                    ],JSON_UNESCAPED_UNICODE));
        }
    }
}
