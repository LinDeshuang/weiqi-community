<?php
namespace app\common\controller;

use think\Controller;
use think\Session;
use think\Loader;

class AdminBase extends Controller
{
    protected function _initialize()
    {
        parent::_initialize();

        //获取当前的控制器名和方法名
        $this->assign('controller', Loader::parseName($this->request->controller()));
        $this->assign('action', Loader::parseName($this->request->action()));
        $login_res = Session::get('user_id');
        $user_account = Session::get('user_account');
        $this->assign('user_account',$user_account);
        if(!$login_res)
        {
            $this->error('尚未登录或登录已失效，正在前往登录页面','admin/login/index');
        }
    }
}
