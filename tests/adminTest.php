<?php
/**
 * Created by PhpStorm.
 * User: weipinglee
 * Date: 2018/6/7
 * Time: 9:17
 */
namespace tests;
use \app\admin\service\Admin;


class adminTest extends TestCase
{



    public function testadminServiceModel(){

        $adminModel =  new Admin();
        $data = array('adminname'=>'weiping','password'=>'123456');
        $adminModel->add($data);
        $data['password'] = md5($data['password']);
        $this->seeInDatabase('admin',$data);
    }






}