<?php
namespace User\Controller;
use Think\Controller;

/**
 * @Author: Zhou Yuyang <1009465756@qq.com> 10:28 2016/12/03
 * @Copyright 2015-2020 Plusec All Rights Reserved
 * @Project homepage https://github.com/martinzhou2015/SRCMS
 * @Version 3.0 Alpha
 */
 
class PostController extends BaseController
{

    public function index($key="")
    {
        
        if($key == ""){
            $model = D('PostView'); 
        }else{
            $where['post.title'] = array('like',"%$key%");
            $where['category.title'] = array('like',"%$key%");
            $where['_logic'] = 'or';
            $model = D('PostView') -> where($where); 
        } 
        
        $id = session('userId');
        $count  = $model->where($where)->where('user_id='.$id)->count();
        $Page = new \Extend\Page($count,20);
        $show = $Page->show();
        $post = $model->order('post.id DESC')->where(array('user_id'=>$id)) ->limit($Page->firstRow.','.$Page->listRows) -> select();
        //fix bug 2017-11-02 by. yuyang
        $this->assign('model', $post);
        $this->assign('page',$show);
        $this->display();     
    }
	
    public function filter()
    {
        $type = I('get.tid','','intval');
        $id = session('userId');
        $count  = M('post') -> where($where) -> where(array('user_id'=>$id,'type'=>$type)) -> count();
        $Page = new \Extend\Page($count,20);
        $show = $Page->show();
        $post = M('post') ->limit($Page->firstRow.','.$Page->listRows)->where(array('user_id'=>$id,'type'=>$type))->order('post.id DESC')->select();
        $this->assign('model', $post);
        $this->assign('page',$show);
        $this->display();     
    }
	
    public function add()
    {
        //默认显示添加表单
        if (!IS_POST) {
			$tmodel= M('setting');
		    $title = $tmodel->where('id=1')->select();
		    $this->assign('title', $title);
        	$this->assign("category",getSortedCategory(M('category')->select()));
            $this->display();
        }
        if (IS_POST) {
            //如果用户提交数据
            $model = D("Post");
            $model->create_time = time();
			$data = I();
            $token = $data['token'];
			if($token != session('token')){
				$this->error("非法请求");
			}
            if (!$model->create()) {
                // 如果创建失败 表示验证没有通过 输出错误提示信息
                $this->error($model->getError());
                exit();
            } else {
                if ($model->add()) {
					require "./././././ThinkPHP/Library/Org/Net/Mail.class.php";
					$time = date("Y-m-d h:i:sa");
					$con='您好,安全应急响应中心新增一份漏洞报告《 '.$data['title'].'》。请您及时登陆后台查看。';  
					SendMail('1009465756@qq.com','新增漏洞报告提示',$con,'安全应急响应中心');
                    $this->success("报告成功", U('post/index'));
                } else {
                    $this->error("报告失败");
                }
            }
        }
    }
    
	public function edit()
    {
        //默认显示添加表单
        if (!IS_POST) {
            $tmodel= M('setting');
            $rid = I('get.rid',0,'intval');
            $uid = session('userId');
            $post = M('post')->where(array('user_id'=>$uid,'id'=>$rid))->find();
            if ($post == NULL){
                $this -> error('非法操作',U('Post/index'));
            }
            if ($post['type'] != 1){
                $this -> error('漏洞已经审核，无法操作',U('Post/index'));
            }
			$title = $tmodel->where('id=1')->select();
		    $this->assign('title', $title);
            $this->assign('content', $post);
        	$this->assign("category",getSortedCategory(M('category')->select()));
            $this->display();
        }
        if (IS_POST) {
            //如果用户提交数据
            $model = D("Post");
            $model->time = time();
			$data = I();
            $rid = I('get.rid',0,'intval');
            $uid = session('userId');
            if ($model->where(array('id'=>$rid,'user_id'=>$uid))->field('title,user_id,cate_id,content')->save($data)) {
					require "./././././ThinkPHP/Library/Org/Net/Mail.class.php";
					$time = date("Y-m-d h:i:sa");
					$con='您好,安全应急响应中心新增一份漏洞报告《 '.$data['title'].'》。请您及时登陆后台查看。';  
					SendMail('1009465756@qq.com','新增漏洞报告提示',$con,'安全应急响应中心');
                    $this->success("修改成功", U('post/view?rid=').$rid);
                } else {
                    $this->error("修改失败");
                }
        }
    }
	public function view(){
	    $rid = I('get.rid',0,'intval');
		$model = M("Post");
		$id = session('userId');
        $post = $model->where(array('user_id'=>$id,'id'=>$rid))->find();
        if ($post == NULL){
            $this -> error('非法操作',U('Post/index'));
        }
		$comment = M('comment')->where(array('post_id'=>$rid))->select();
		$tmodel= M('setting');
		$title = $tmodel->where('id=1')->select();
		$this->assign('title', $title);
        $this->assign('model', $post);
		$this->assign('comment',$comment);
        $this->display();
    }
	
	public function comment()
    {
        if (!IS_POST) {
        	$this->error("非法请求");
        }
        if (IS_POST) {
			$data = I();
			$data['update_time'] = time();
			$data['user_id'] = session('username');
			$model = M("Comment");
            if ($model->add($data)) {
                    $this->success("评论成功", U('post/index'));
                } else {
                    $this->error("评论失败");
                }
        }
    }
}
