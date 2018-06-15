<?php

namespace app\admin\Controller;
use think\Controller;
use think\Request;
use think\Session;

class Base extends Controller{


    public function _initialize()
    {
        parent::_initialize();


        //判断登录
        if(!Session::get('id'))
            $this->redirect('login/login');

        //get module,controller,action name
        $request = Request::instance();



        $routParam = array(
            'module'=>$request->module(),
            'controller'=>$request->controller(),
            'action' => $request->action()
        );

        if($routParam['controller']=='Index'){
            return true;
        }

        //权限管理
        $priModel = model('Privilege');
        if(!$priModel->chkPri($routParam))
        {
            $request = Request::instance();
            if($request->isAjax()){
                die(json_encode(array('success'=>0,'info'=>'您无权限操作此项')));
            }
            $this->redirect('admin/index/index');
        }

    }



}