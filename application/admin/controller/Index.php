<?php
// +----------------------------------------------------------------------
// | @date: 2017/11/22 - 11:36
// +----------------------------------------------------------------------
// | @author：Ulex
// +----------------------------------------------------------------------
// | @desc：后台首页
// +----------------------------------------------------------------------
namespace app\admin\Controller;

use \think\Loader;
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
        //配置信息
        $configModel = Loader::model('Config','service');
        $configData = $configModel->row(1);
        $this->assign('config',array('days'=>$configData['overdue_days']));

        //贷款超期信息
        $service = \think\Loader::model('Loan','service');
        $where = 'l.del=0 and  status="已提交" and ADDDATE(now(),'.$configData['overdue_days'].') > l.end_date ';
        $loanList = $service->lists($where,1,1000);

        $service = \think\Loader::model('Storage','service');
        $where = 'l.del=0 and  status="已提交" and ADDDATE(now(),'.$configData['overdue_days'].') > l.end_date ';
        $storageList = $service->lists($where,1,1000);

        $this->assign('loanList',$loanList['data']);
        $this->assign('storageList',$storageList['data']);





        return $this->fetch('index_v1');
    }
}