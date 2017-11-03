<?php
namespace User\Controller;
use Think\Controller;

/**
 * @Author: Zhou Yuyang <1009465756@qq.com> 10:28 2016/12/03
 * @Copyright 2015-2020 Plusec All Rights Reserved
 * @Project homepage https://github.com/martinzhou2015/SRCMS
 * @Version 3.0 Alpha
 */

class InfoController extends BaseController{

	/**
     * 更新联系方式
     */
	public function index()
    {
		$id = session('userId');
		
        if (!IS_POST) {
            $info = M('member')->where(array('id'=>$id))->select();
            $this->assign('info',$info);
            $this->display();
        }
        if (IS_POST) {
            $model = M("member");
		    $data = I();
			$token = $data['token'];
			$user = $model->where(array('id'=>$id))->find();
			
	        if($token != $user['token']){
				$this->error("非法请求");
			}
			
            if ($model->where(array('id'=>$id))->field('realname,zipcode,address,tel,alipay,bankcode,idcode,qqnumber,website,description,qqnumber,idcode,team')->save($data)) {
                    $this->success("联系方式更新成功", U('info/index'));
                } else {
                    $this->error("联系方式更新失败", U('info/index'));
            }
        }
    }

}
