<?php
namespace app\admin\event;

use think\Controller;
use think\Request;

abstract class Base {



    protected $serviceModel = null;
    protected $login = array();

    public function __construct(Request $request = null)
    {
        $this->serviceModel = \think\Loader::model('Loan','service');
        $loginObj = \think\Loader::model('Admin');
        $this->login = $loginObj->logData();

    }

    abstract public function lst($where=array(),$page=1,$pagesize=10);

    abstract public function row($id);

    abstract public function add($data);

    abstract public function edit($id,$data);

    abstract public function delete($id);


}