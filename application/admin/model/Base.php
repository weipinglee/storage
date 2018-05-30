<?php
// +----------------------------------------------------------------------
// | @date: 2018/05/28 - 10:49
// +----------------------------------------------------------------------
// | @author：weipinglee
// +----------------------------------------------------------------------
// | @desc：
// +----------------------------------------------------------------------
namespace app\admin\Model;

use app\admin\validate\MyValidate;


class Base{


    protected $validateObj = null;//验证类
    protected $rules = array();//验证规则
    protected $insertRules = array();
    protected $updateRules = array();
    protected $searchFields = array();
    protected $message = array();//验证消息
    protected $pk = 'id';

    protected $tableName = '';

    public function __construct()
    {
        $this->validateObj = new MyValidate($this->rules,$this->message);
    }

    public static function getSuccInfo($res=1,$info='',$id='',$time=1){
        return array('success'=>$res,'info'=>$info,'id'=>$id,'time'=>intval($time));
    }

    public function getTable(){
        return $this->tableName;
    }


    public function searchFields(){
        return $this->searchFields;
    }

    public function getPk(){
        return $this->pk;
    }

    /**
     * 验证数据
     * @param $data
     * @param $error
     * @return bool
     */
    public function check($data,&$error){
        if($this->validateObj->check($data,$this->rules)){
            return true;
        }
        $error = $this->validateObj->getError();
        return false;
    }

    public function checkInsert($data,&$error){
        if($this->validateObj->check($data,array_merge($this->insertRules,$this->rules))){
            return true;
        }
        $error = $this->validateObj->getError();
        return false;
    }

    public function checkUpdate($data,&$error){
        if($this->validateObj->check($data,array_merge($this->updateRules,$this->rules))){
            return true;
        }
        $error = $this->validateObj->getError();
        return false;
    }


}