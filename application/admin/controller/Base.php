<?php

namespace app\admin\Controller;
use think\Controller;


class Base extends Controller{

    public function __construct()
    {
        //必须先调用父类的构造方法
        parent::__construct();
        //判断登录

        if(!session('id'))
            $this->redirect('login/login');
        //所有管理员都可以访问首页
        if(CONTROLLER_NAME == 'Index')
            return true;
        $priModel = new Privilege();
        if(!$priModel->chkPri())
        {
            $this->error('无权访问！');
        }

    }



}