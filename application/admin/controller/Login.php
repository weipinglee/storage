<?php
namespace app\admin\Controller;

use think\Controller;
use think\Request;
use think\Db;
use \extDB\DbModel;
class Login extends Controller {
    /**
     * 后台登录
     */
    public function login()
    {
         $this->assign('url',url('admin/login/doLogin'));
         return $this->fetch('login');

    }

    public function doLogin(){
        $request = Request::instance();

        $model =  model('Admin');

        $res = $model->login($request->param('adminname'),$request->param('password'));

        die(json_encode($res));

    }

    /**
     * 退出登录
     */
    public function logout()
    {
        $model = model('Admin');
        $model->logout();
        $this->redirect('admin/login/login');
    }
    /**
     * 制作验证码
     */
    public function chkcode(){

    }
}