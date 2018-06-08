<?php
/**
 * Created by PhpStorm.
 * User: weipinglee
 * Date: 2018/6/7
 * Time: 9:17
 */
namespace tests;
use \app\admin\service\Admin;
use \think\Request;
use \think\Db;
use \extDB\DbModel;
class adminTest extends TestCase
{

    public static function setUpBeforeClass(){
        //设置测试数据库
        \think\Config::set('database.database','test_loan');
        //设置请求信息,否则应用中找不到模型类
        Request::instance(array('module'=>'admin'));
    }

    public function testadminServiceModel(){

        $adminModel =  new Admin();
        $data = array('adminname'=>'weiping','password'=>'123456');
        $adminModel->add($data);
        $data['password'] = md5($data['password']);
        $this->seeInDatabase('admin',$data);
    }

    public static  function tearDownAfterClass(){
        $dbObj = new DbModel('');
        $sql = "truncate table  st_admin";
        $res = $dbObj->query($sql);
        echo $res;
    }




}