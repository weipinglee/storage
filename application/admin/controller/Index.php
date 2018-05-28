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
    public function index()
    {
        $priObj = model('Privilege');
        $menus = $priObj->getMenus();//print_r($menus);
        $this->assign('menus',$menus);
        return $this->fetch('index');
    }
}