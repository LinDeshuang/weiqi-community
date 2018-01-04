<?php
namespace app\admin\controller;

use think\Controller;
use think\Session;
use think\Config;
use think\Loader;
use app\common\validate\User as user_validate;
use app\common\model\User as user_model;
/**
*登录及注册控制器
*/
class Login extends Controller
{
	protected $user_model;
	protected $user_validate;
	protected function _initialize()
	{
		parent::_initialize();
        $this->assign('controller', Loader::parseName($this->request->controller()));
        $this->assign('action', Loader::parseName($this->request->action()));
		$this->user_model = new user_model();
		$this->user_validate = new user_validate();
	}
/**
*登录页面
*/
    public function index()
    {
    	return $this->fetch();
    }
/**
*注册
*/
    public function register()
    {
    	if($this->request->isPost())
    	{
    		$data = $this->request->post();
    		if(!$this->user_validate->check($data))
    		{
    			$errMsg  = json_decode($this->user_validate->getError(),true);
    			$errCode = '40200';
    		}
    		else
    		{
    			$data['pwd'] = md5($data['pwd'].Config::get('salt'));
    			$res = $this->user_model->allowField(true)->save($data);
    			if($res)
    			{
    				$errMsg = '注册成功';
    				$errCode = 0;
    			}
    			else
    			{
    				$errMsg = '注册失败';
    				$errCode = '40100';
    			}
    		}
    				exit(json_encode([
    							'errcode' => $errCode,
    							'errmsg' => $errMsg
    						],JSON_UNESCAPED_UNICODE));
    	}
    	else
    	{
    		return $this->fetch();
    	}
    }
/**
*登录处理
*/
	public function login()
	{
		if($this->request->isPost())
		{
			$data = $this->request->post(); 
			$res = captcha_check($data['verify_code']);
			if(!$res)
			{
				$errCode = '40100';
				$errMsg  = '验证码错误';
			}
			else
			{
				$account = $data['account'];
				$pwd     = md5($data['pwd'].Config::get('salt'));
				$login_res = $this->user_model->where([ 'account' => $account])->find();
				if(!empty($login_res))
				{
					$login_res = $this->user_model->where([ 'account' => $account, 'pwd'=>$pwd])->find();
					if(!empty($login_res))
					{
						$user_id = $login_res['id'];
						Session::set('user_id',$user_id);
						Session::set('user_account',$account);
						$errCode = 0;
						$errMsg  = '登录成功';
					}
					else
					{
						$errCode = '40100';
						$errMsg  = '登录失败，密码错误';
					}
	
				}
				else
				{
					$errCode = '40100';
					$errMsg  = '登录失败，账号不存在';
				}
			}
			exit(json_encode([
							'errcode' => $errCode,
							'errmsg'  => $errMsg
						],JSON_UNESCAPED_UNICODE));

		}
	}
/**
*退出登录
*/
	public function logout()
	{
		 Session::delete('user_id');
         Session::delete('user_account');
        $this->success('退出成功，正在跳转至首页','index/index/index');
	}
}
