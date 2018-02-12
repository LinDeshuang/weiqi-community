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
            $preUrl = isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'';
            // $pattern = '/admin\/login\/index/';
            // $pos = preg_match($pattern,$preUrl);

            $pos = stripos($preUrl,'admin/login/index/pre_url/');

            //重复进入login页的preUrl处理
            if($pos)
            {
              $preUrl = substr($preUrl,0,strlen($preUrl)-5);//去掉.html
              $preUrl = urldecode(urldecode(substr($preUrl,$pos+26)));//取得真正的preUrl
            }
            $this->error('尚未登录或登录已失效','admin/login/index?pre_url='.urlencode(urlencode($preUrl)),30);
        }
    }
}
