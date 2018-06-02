<?php

/**
 * 数据库操作基类，支持多数据库
 * Created by PhpStorm.
 * author: wplee
 * Date: 2016/1/29
 */
namespace extDB\DB;
class DbFactory {

    //用于存放实例化的对象
    static private $_instance = null; //DB实例

	//公共静态方法获取实例化的对象
	static public function getInstance($dbName='PDO') {
		if (self::$_instance == null) {
            switch($dbName){
                case 'PDO' :
                    default : {
                        self::$_instance = new MYPDO();
                     }
                     break;
                case 'thinkDB':{
                    self::$_instance = new ThinkPDO();
                }
            }
		}
		return self::$_instance;
	}

	//私有克隆
	private function __clone() {}



}
?>