<?php
namespace app\admin\controller;

use think\Controller;
use think\Db;
use think\Session;
use think\Validate;
use think\Config;
use app\common\model\User as user_model;
use app\common\controller\AdminBase;

class Index extends AdminBase
{
    protected $user_model;
	protected $user_id;
	protected function _initialize()
	{
		parent::_initialize();
		$this->user_model = new user_model();
        $this->user_id = Session::get('user_id');
	}

	/**
	*棋手中心首页
	*/
    public function index()
    {

    	$user = Db::name('user')->where('id',$this->user_id)->find();
    	$this->assign('user',$user);
    	return $this->fetch();
    }

    /**
    *信息更新
    */
    public function edit()
    {
    	if($this->request->isPost())
    	{
    		$data = $this->request->post();
    		$user = Db::name('user')->where('id',$this->user_id)->find();
    		$rule = [
					'name'    	  => 'require|length:1,20|unique:user',
					'gender'      => 'require',
					'level_type'  => 'require',
					'email'       => 'email'
				];
			if($user['name']==$data['name'])
			{
				$rule['name'] = 'require|length:1,20';
			}
			$message = [
					'name.require'    => '{"name":"name","msg":"昵称不能为空"}',
					'name.length'     => '{"name":"name","msg":"昵称长度不能超过20"}',
					'name.unique'     => '{"name":"name","msg":"昵称已存在，请重新输入"}',
					'gender.require'  => '{"name":"gender","msg":"性别不能为空"}',
					'level_type.require'  => '{"name":"level_type","msg":"段位类型不能为空"}',
					'email.email'     => '{"name":"email","msg":"邮箱格式错误"}'
				];
    		$validate = new Validate($rule,$message);
    		if(!$validate->check($data))
    		{
    			$errMsg  = json_decode($validate->getError(),true);
    			$errCode = '40200';
    		}
    		else
    		{

    			$res = $this->user_model->save($data,['id'=>$this->user_id]);
    			if($res)
    			{
    				$errMsg = '保存成功';
    				$errCode = 0;
    			}
    			else
    			{
    				$errMsg = '保存失败，未更新数据或网络错误';
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
	    	$user = Db::name('user')->where('id',$this->user_id)->find();
	    	$this->assign('user',$user);
	    	return $this->fetch();
    	}

    }

    /**
    *账号设置
    */
    public function account()
    {
    	if($this->request->isPost())
    	{
    		$data = $this->request->post();
    		$user = Db::name('user')->where('id',$this->user_id)->find();
    		$rule = [
					'account' 	  => 'require|length:8,30|alphaNum|unique:user',
					'pwd'	  	  => 'confirm:confirm_pwd|length:6,20',
				];
			if($user['account']==$data['account'])
			{
				$rule['account'] = 'require|length:8,30|alphaNum';
			}
			$message = [
					'account.require' => '{"name":"account","msg":"账号不能为空"}',
					'account.length'  => '{"name":"account","msg":"账号长度在8~30"}',
					'account.alphaNum'=> '{"name":"account","msg":"账号必须由字母和数字组成"}',
					'account.unique'  => '{"name":"account","msg":"账号已存在，请重新输入"}',
					'pwd.require'	  => '{"name":"pwd","msg":"密码不能为空"}',
					'pwd.length'	  => '{"name":"pwd","msg":"密码长度在6~20"}',
					'pwd.confirm'	  => '{"name":"pwd","msg":"两次输入的密码不一致请重新输入"}'
				];
    		$validate = new Validate($rule,$message);
    		$original_pwd = md5($data['original_pwd'].Config::get('salt'));
    		if($original_pwd!=$user['pwd'])
    		{
    			$errMsg = '{"name":"original_pwd","msg":"密码错误"}';
				$errCode = '40200';
				exit(json_encode([
					'errcode' => $errCode,
					'errmsg' => json_decode($errMsg,true)
					],JSON_UNESCAPED_UNICODE));
    		}
	   		if(!$validate->check($data))
    		{
    			$errMsg  = json_decode($validate->getError(),true);
    			$errCode = '40200';
    		}
    		else
    		{
    			if(empty($data['pwd']))
    			{
    				unset($data['pwd']);
    			}
    			else
    			{
    				$data['pwd'] = md5($data['pwd'].Config::get('salt'));
    			}
    			$res = $this->user_model->save($data,['id'=>$this->user_id]);
    			if($res)
    			{
    				$errMsg = '修改成功';
    				$errCode = 0;
    			}
    			else
    			{
    				$errMsg = '修改失败，未更新数据或网络错误';
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
	    	$user = Db::name('user')->where('id',$this->user_id)->find();
	    	$this->assign('user',$user);
	    	return $this->fetch();
    	}
    }

    /**
    *头像设置
    */
    public function photo()
    {
        $user = Db::name('user')->where('id',$this->user_id)->find();
        $this->assign('user',$user);
        return $this->fetch();            

    }

    /**
    *头像上传
    */
    public function upPhoto()
    {
        $file = $this->request->file('photo');
        $info = $file->validate(['size'=>1024*1024,'ext'=>'png,jpg,gif,jpeg'])->move(ROOT_PATH . 'public' . DS . 'uploads' . DS . 'user' . $this->user_id . 'img/headphoto/');
        if($info)
        {
            // 成功上传后 获取上传信息
            $ext = $info->getExtension();
            $saveName =  $info->getSaveName();
            $fileName =  $info->getFilename();
            $photoPath = '/uploads/user'.$this->user_id.'img/headphoto/'.str_replace("\\", "/", $saveName);
            $res      = $this->user_model->save(['photo'=>$photoPath],['id'=>$this->user_id]);

            if($res)
            {
                $errCode  = 0;
                $errMsg   = 'ok'; 
            }
            else
            {
                $errCode  = '40100';
                $errMsg   = '设置失败，未知错误';
            }

        }
        else
        {
        // 上传失败获取错误信息
        $errmsg =  $file->getError();
        $errCode  = '40100';
        }
        exit(json_encode([
            'errcode' => $errCode,
            'errmsg' => $errMsg
            ],JSON_UNESCAPED_UNICODE));
    }
}
