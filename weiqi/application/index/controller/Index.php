<?php
namespace app\index\controller;

use think\Controller;
use think\Request;
use think\Db;
use think\Config;
use think\Session;

class Index extends Controller
{
    public function index()
    {

    	$manual_info = Db::view('WeiqiManual','id , user_id , manual_name , manual_intro , create_time , share_time , manual_status , delete_time , click_count , praise_count , comment_count')->view('User','name as user_name','User.id = Weiqimanual.user_id','RIGHT')->where(['manual_status'=>1])->whereNull('WeiqiManual.delete_time')->order('create_time','desc')->paginate(5);
    	if($this->request->isPost())
    	{
	    	
	    	exit(json_encode($manual_info,JSON_UNESCAPED_UNICODE));
    	}
    	else
    	{
    		$this->assign('manual_info',$manual_info);
 			return $this->fetch();
    	}
    }
    public function notion()
    {

        $notion_info = Db::view('Notion','id , user_id , create_time , notion_content , status , delete_time , praise_count, comment_count')->view('User','name as user_name , photo','User.id = Notion.user_id','RIGHT')->where(['Notion.status'=>1])->whereNull('Notion.delete_time')->order('create_time','desc')->paginate(7);
        if($this->request->isPost())
        {
            
            exit(json_encode($notion_info,JSON_UNESCAPED_UNICODE));
        }
        else
        {
            $this->assign('notion_info',$notion_info);
            return $this->fetch();
        }
    }

    public function playManual($id)
    {
        $manual = Db::view('WeiqiManual','id , user_id , manual_name , manual_intro , manual_data , create_time , share_time , manual_status , click_count ,praise_count,comment_count')->view('User','id as user_id, name as user_name','User.id = Weiqimanual.user_id','RIGHT')->where(['WeiqiManual.id'=>$id])->find();
        //点击量增加机制
        if( !Session::has($manual['user_id'].'_'.$id) )
            {
                Session::set($manual['user_id'].'_'.$id,'click');
                Db::name('WeiqiManual')->where(['id'=>$id])->setInc('click_count');
            }
        $comment = Db::view('ManualComment','id , user_id , manual_id , pid , comment_content , create_time , comment_status')->view('WeiqiManual','id as manual_id','ManualComment.manual_id = WeiqiManual.id')->view('User','name as user_name','User.id = ManualComment.user_id','RIGHT')->where(['ManualComment.manual_id'=>$id , 'comment_status'=>1])->order('create_time','desc')->select();
        $comment = array2level($comment);
        foreach ($comment as $key => $value) {
            if($value['pid']!=0){
                foreach ($comment as $k => $v) {
                    if($v['id'] == $value['pid']){
                        $origin_comment = $v['comment_content'];
                        $origin_user = $v['user_name'];
                        $comment[$key]['origin_user']=$origin_user;
                        $comment[$key]['origin_comment']=$origin_comment;
                    }
                }
            }
        }
        $this->assign('manual' , $manual);
        $this->assign('comment' , $comment);
        return $this->fetch();
    }
}
