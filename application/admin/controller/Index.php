<?php
// +----------------------------------------------------------------------
// | @date: 2017/11/22 - 11:36
// +----------------------------------------------------------------------
// | @author：Ulex
// +----------------------------------------------------------------------
// | @desc：后台首页
// +----------------------------------------------------------------------
namespace app\admin\Controller;

class Index extends Base
{

    public function _initialize()
    {
        parent::_initialize();
    }

    public function index()
    {
        $priObj = model('Privilege');
        $menus = $priObj->getMenus();//print_r($menus);
        $this->assign('menus',$menus);
        return $this->fetch('index');
    }

    public function index_v1(){
        return $this->fetch('index_v1');
    }
}