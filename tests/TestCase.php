<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2015 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: yunwuxin <448901948@qq.com>
// +----------------------------------------------------------------------
namespace tests;
use \think\Request;
use \think\Db;
use \extDB\DbModel;
class TestCase extends \think\testing\TestCase
{
    protected $baseUrl = 'http://localhost';

    public static function setUpBeforeClass(){
        //设置测试数据库
        \think\Config::set('database.database','test_loan');
        //设置请求信息,否则应用中找不到模型类
        Request::instance(array('module'=>'admin'));
    }


    public static  function tearDownAfterClass(){
        $dbObj = new DbModel('');
        $sql = "truncate table  st_admin";
        $res = $dbObj->query($sql);
        echo $res;
    }

}