<?php

namespace app\admin\Controller;
use think\Controller;
use think\Request;


class Base extends Controller{


    public function _initialize()
    {
        parent::_initialize();


        //判断登录
        if(!session('id'))
            $this->redirect('login/login');

        //get module,controller,action name
        $request = Request::instance();

        return true;//暂部分权限

        $routParam = array(
            'module'=>$request->module(),
            'controller'=>$request->controller(),
            'action' => $request->action()
        );

        if($routParam['controller']=='Index'){
            return true;
        }

        //权限管理后续再加
        $priModel = model('Privilege');
        if(!$priModel->chkPri())
        {
            $this->error('无权访问！');
        }

    }



}