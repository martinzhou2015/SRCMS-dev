<?php
namespace User\Controller;
use Think\Controller;

/**
 * @Author: Zhou Yuyang <1009465756@qq.com> 10:28 2016/12/03
 * @Copyright 2015-2020 Plusec All Rights Reserved
 * @Project homepage https://github.com/martinzhou2015/SRCMS
 * @Version 3.0 Alpha
 */

class GiftController extends BaseController{

    public function index(){
		$id = session('userId');
        $username = session('username');
		$gifts = M('links');
		$count  = $gifts->where($where)->count();
        $Page = new \Extend\Page($count,8);
		$show = $Page->show();// 分页显示输出
		$pages = $gifts->limit($Page->firstRow.','.$Page->listRows)->where($where)->order('id DESC')->select();
        $record_num = M('record') -> count();
        $jinbi = M('member')->where('id='.$id)->find();
        $giftnum = M('order')->where(array('username'=>$username,'userid'=>$id))->count();
        $this->assign('gift',$pages);
		$this->assign('page',$show);
		$info = M('member')->where('id='.$id)->select();
		$this->assign('info',$info);
        $this->assign('recordnum',$record_num);
        $this->assign('jinbi',$jinbi);
        $this->assign('giftnum',$giftnum);
        $this->display();
    }
		
	 public function order(){
		$id = session('userId');
		$username = session('username');
		$info = M('order')->where(array('username'=>$username,'userid'=>$id))->select();
		$this->assign('info',$info);
        $this->display();
    }
	
	
    public function record(){
		$id = session('userId');
        $db = M('record');
		$username = session('username');
        $count  = $db -> count();
        $Page = new \Extend\Page($count,20);
		$show = $Page->show();           
		$record = $db -> where(array('user'=>$username,'userid'=>$id)) -> limit($Page->firstRow.','.$Page->listRows) -> select();
		$this -> assign('record',$record);
        $this -> assign('page',$show);
        $this -> display();
    }
	
	
	public function add()
    {
		$id = session('userId');
		$gid = I('get.gid',0,'intval');
        if (!IS_POST) {
            $info = M('member')->where('id='.$id)->find();
			$gift = M('links')->where('id='.$gid)->find();
            $this->assign('info',$info);
			$this->assign('gift',$gift);
            $this->display();
        }
        if (IS_POST) {
            $id = session('userId');
            $model = M("order");
			$record = M('record');
			$user = M('member')->where('id='.$id)->find();
			$gift = M('links')->where('id='.$gid)->find();
			if($user['jinbi']<$gift['price']){
				$this->error("安全币余额不足!", U('gift/index'));
				exit();
			}
            $data = I();
            if($data['num']<0){
                $this->error("兑换数量非法！", U('gift/index'));
				exit();
            }
            $price = $gift['price'] * $data['num'];
			$data['gid'] = $gift['title']; 
			$data['price'] = $gift['price']; 
			$data['username'] = session('username');
			$data['userid'] = session('userId');
			$data['update_time'] = time();
			
			//记录兑换安全币变动日志
			$rdata['type'] = 1;
			$rdata['name'] = '兑换'.$gift['title'];
            $rdata['num'] = '数量:'.$gift['num'];
			$rdata['content'] = '-安全币:'.$price;
			$rdata['time'] = time();
			$rdata['user'] = session('username');
			$rdata['userid'] = session('userId');
			$rdata['operator'] = session('username');
			$record_result = $record -> add($rdata);
			
			$token = $data['token'];
			if($token != $user['token']){
				$this->error("非法请求");
			}
		    if($user['jinbi']<$price){
				$this->error("安全币余额不足!", U('gift/index'));
				exit();
			}
			$result = M('member')->where('id='.$id)->setDec('jinbi',$price);
            if (!$result){
               $this->error("兑换失败", U('gift/index'));
			}
            if ($model->field('userid,username,gid,tel,alipay,realname,address,zipcode,price,update_time,num')->add($data)) {
				if($result){
                    $this->success("兑换成功", U('gift/order'));
					}
				else{
					$this->error("兑换失败", U('gift/index'));
				}
                } else {
                    $this->error("兑换失败", U('gift/index'));
                }
		}
	}
}