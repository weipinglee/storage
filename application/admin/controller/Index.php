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
        $model = D('Privilege');
        $menus = $model->getMenus();
//        var_dump($menus);exit();
        $this->assign(
            'menus', $menus
        );
        $this->display();
    }
}