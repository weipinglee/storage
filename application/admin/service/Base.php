<?php
// +----------------------------------------------------------------------
// | @date: 2018/05/28 - 10:49
// +----------------------------------------------------------------------
// | @author：weipinglee
// +----------------------------------------------------------------------
// | @desc：
// +----------------------------------------------------------------------
namespace app\admin\service;

use \extDB\DbModel;
abstract class Base{

    protected $errors = '';

    protected $pk = 'id';

    protected $dbObj = null;
    protected $model = null;
    protected $tableName = '';


    public static function getSuccInfo($res=1,$info='',$id='',$time=1){
        return array('success'=>$res,'info'=>$info,'id'=>$id,'time'=>intval($time));
    }

    abstract public function row($id);

    /**
     * @method put
     * @param $data
     * @return array
     */
    abstract public function add($data);

    /**
     * @method delete
     * @param int $id
     * @return mixed
     */
    abstract public function del($id);

    /**
     * @method post
     * @param $id
     * @param $data
     * @return mixed
     */
    abstract public function edit($id,$data);


    abstract public function lists($page=1,$where='');



}