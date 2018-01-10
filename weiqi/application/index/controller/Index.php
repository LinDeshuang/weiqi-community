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

    	if($this->request->isPost())
    	{
            $data = $this->request->post();
            switch ($data['order_by']) {
                case 1:
                    $order = 'click_count';
                    break; 
                case 2:
                    $order = 'praise_count';
                    break;  
                case 3:
                    $order = 'comment_count';
                    break;
                default:
                    $order = 'create_time';
                    break;
            }
            if(empty($data['search_content']))
            {
                $map = [
                    'manual_status'=>1
                ];
            }
            else
            {
                $map = [
                    'manual_status'=>1,
                    'manual_name'  =>['like','%'.$data['search_content'].'%'] 
                ];
            }
	        $manual_info = Db::view('WeiqiManual','id , user_id , manual_name , manual_intro , create_time , share_time , manual_status , delete_time , click_count , praise_count , comment_count')->view('User','name as user_name','User.id = Weiqimanual.user_id','RIGHT')->where($map)->whereNull('WeiqiManual.delete_time')->order($order,'desc')->paginate(5);
	    	exit(json_encode($manual_info,JSON_UNESCAPED_UNICODE));
    	}
    	else
    	{
 			return $this->fetch();
    	}
    }
    /**
    *notion列表
    */
    public function notion()
    {


        if($this->request->isPost())
        {
             $data = $this->request->post();
             switch ($data['order_by']) {
                case 1:
                    $order = 'create_time';
                    break; 
                case 2:
                    $order = 'praise_count';
                    break;  
                case 3:
                    $order = 'comment_count';
                    break;
                default:
                    $order = 'create_time';
                    break;
            }
            if(empty($data['search_content']))
            {
                $map = [
                    'notion_status'=>1
                ];
            }
            else
            {
                $map = [
                    'notion_status'=>1,
                    'notion_content'  =>['like','%'.$data['search_content'].'%'] 
                ];
            }
            $notion_info = Db::view('Notion','id , user_id , create_time , notion_content , notion_status , delete_time , praise_count, comment_count')->view('User','name as user_name , photo','User.id = Notion.user_id','RIGHT')->where($map)->whereNull('Notion.delete_time')->order($order,'desc')->paginate(7);
            exit(json_encode($notion_info,JSON_UNESCAPED_UNICODE));
        }
        else
        {
            return $this->fetch();
        }
    }
    /**
    * notion 评论详情
    */
    public function notionComment($id)
    {
        $notion = Db::view('Notion','id , user_id , create_time , notion_content , notion_status , delete_time , praise_count, comment_count')->view('User','name as user_name , photo','User.id = Notion.user_id','RIGHT')->where('Notion.id',$id)->find();
        $notion_comment = Db::view('NotionComment','id , user_id , notion_id , pid , comment_content , create_time , comment_status')->view('Notion','id as notion_id','NotionComment.notion_id = Notion.id')->view('User','name as user_name','User.id = NotionComment.user_id','RIGHT')->where(['NotionComment.notion_id'=>$id , 'comment_status'=>1])->order('create_time','desc')->select();
        $notion_comment = array2level($notion_comment);
        foreach ($notion_comment as $key => $value) {
                if($value['pid']!=0){
                    foreach ($notion_comment as $k => $v) {
                        if($v['id'] == $value['pid']){
                            $origin_comment = $v['comment_content'];
                            $origin_user = $v['user_name'];
                            $notion_comment[$key]['origin_user']=$origin_user;
                            $notion_comment[$key]['origin_comment']=$origin_comment;
                        }
                    }
                }
            }
        $this->assign('notion',$notion);
        $this->assign('notion_comment',$notion_comment);
        return $this->fetch();
    } 
    /**
    *演示棋谱
    */
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

    /**
    *棋手信息
    */
    public function userDetail($id)
    {
        $user_info = Db::name('user')->where('id',$id)->find();
        $preUrl = $_SERVER['HTTP_REFERER'];
        $this->assign('user_info',$user_info);
        $this->assign('prefer_url',$preUrl);
        return $this->fetch();
    }
}
