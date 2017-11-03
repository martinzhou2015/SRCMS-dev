<?php
namespace User\Controller;
use Think\Controller;

/**
 * @Author: Zhou Yuyang <1009465756@qq.com> 10:28 2016/12/03
 * @Copyright 2015-2020 Plusec All Rights Reserved
 * @Project homepage https://github.com/martinzhou2015/SRCMS
 * @Version 3.0 Alpha
 */


class BaseController extends Controller {
    public function _initialize(){
        $sid = session('userId');
        //判断用户是否登陆
        if(!isset($sid ) ) {
            redirect(U('Login/index'));
        }

    }

}