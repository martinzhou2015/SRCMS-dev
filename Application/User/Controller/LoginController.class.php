<?php
namespace User\Controller;
use Think\Controller;

/**
 * @Author: Zhou Yuyang <1009465756@qq.com> 10:28 2016/12/03
 * @Copyright 2015-2020 Plusec All Rights Reserved
 * @Project homepage https://github.com/martinzhou2015/SRCMS
 * @Version 3.0 Alpha
 */

class LoginController extends Controller {
    /**
    登陆页面
    **/
    public function index(){
		$tmodel= M('setting');
		$title = $tmodel->where('id=1')->select();
		$this->assign('title', $title);
        $this->display();
    }
	
    public function svalid(){
        $email =I('get.email','','email');
		$this->assign('email', $email);
		$this->display();
    }

	public function valid(){
        if(!IS_POST){$this->error("非法请求");}
		$code = I('verify','','strtolower');
		$email =I('get.email','','email');
		$token = session('token');
        $login_mark = session('loginmark');
		$member = M('member');
		$user = $member->where(array('email'=>$email))->find();
		if($token != $user['token']){$this->error("非法请求");}
        //验证验证码是否正确
        if(!($this->check_verify($code))){
		   session('userId',null);
		   session('username',null);
           $this->error('验证码错误',U('Login/index'));
        }
		//如果验证码校验成功，跳转到后台主页
        if($login_mark != 'invalid'){
            session('userId',$user['id']);
            session('username',$user['username']);
            $this->success("登陆成功",U('Index/index'));
        }
        else{
            session('loginmark',null);
            $this->error('账号或密码错误',U('Index/index')) ;
        }
    }
	
    /**
    登陆验证
    Valid the login attempt.
    **/
    public function login(){
        if(!IS_POST){$this->error("非法请求");}
        $member = M('member');
		$username = I('username','','htmlspecialchars');
        $password = I('password');
        $user = $member->where(array('username'=>$username))->find();
        
		if($user['password'] != md5(md5(md5($user['salt']).md5($password)."SR")."CMS")) {
            session('loginmark','invalid');
            $this->success("请先完成验证",U('Login/svalid?email=').$user['email']);
        }
		
        if($user['status'] == 0){
            $this->error('账号被禁用，请联系管理员 :(') ;
        }
		$token = md5(md5($user['email'].time()).time());
        //更新登陆信息
        $data =array(
            'id' => $user['id'],
            'update_at' => time(),
            'login_ip' => get_client_ip(),
			'token' => $token 
            //2017-07-02 fix bug: token can't be inserted into databease. By. yuyang
        );
        //登陆成功
        if($member->save($data)){
		    session('token',$token);
            $this->success("请先完成验证",U('Login/svalid?email=').$user['email']);
        }   

    }
	
	//验证码
    public function verify(){
		ob_clean();
        $Verify = new \Think\Verify();
        $Verify->codeSet = '123456789abcdefg';
        $Verify->fontSize = 16;
        $Verify->length = 4;
        $Verify->entry();
    }
    protected function check_verify($code){
        $verify = new \Think\Verify();
        return $verify->check($code);
    }

	
    //退出登录
    public function logout(){
        session('userId',null);
        session('username',null);
        redirect(U('Login/index'));
    }
}