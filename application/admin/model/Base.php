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


abstract class Base{


    protected $validateObj = null;//验证类
    protected $rules = array();//验证规则
    protected $insertRules = array();
    protected $updateRules = array();
    protected $message = array();//验证消息
    protected $fieldType = array();//字段类型
    protected $pk = 'id';
    protected $fieldDesc = array();

    protected $tableName = '';

    public function __construct()
    {
        $this->validateObj = new MyValidate($this->rules,$this->message,$this->fieldDesc);
    }

    public static function getSuccInfo($res=1,$info='',$id='',$time=1){
        return array('success'=>$res,'info'=>$info,'id'=>$id,'time'=>intval($time));
    }

    public function getTable(){
        return $this->tableName;
    }


    public function getPk(){
        return $this->pk;
    }

    /**
     * 如果某字段数据为空，将其转为相应类型的默认值，比如float类型转为0
     * @param $data
     */
    public function typeTrans(&$data){
        $types = $this->fieldType;
        if(!empty($types)){
            foreach($data as $key=>$val){
                if($data[$key]=='' && isset($types[$key])){
                    switch ($types[$key]){
                        case 'int' : $data[$key] = intval($data[$key]);
                        break;
                        case 'float' : $data[$key] = floatval($data[$key]);
                        break;
                        case 'date' :
                        case 'datetime' : $data[$key] = NULL;
                        break;
                     }

                }
            }
        }
    }

    /**
     * 验证数据
     * @param $data
     * @param $error
     * @return bool
     */
    public function check(&$data,&$error){
        if($this->validateObj->check($data,$this->rules)){
            return true;
        }
        $error = $this->validateObj->getError();
        return false;
    }

    public function checkInsert(&$data,&$error){
        if($this->validateObj->check($data,array_merge_recursive($this->insertRules,$this->rules))){
            $this->typeTrans($data);
            return true;
        }
        $error = $this->validateObj->getError();
        return false;
    }

    public function checkUpdate(&$data,&$error){
        if($this->validateObj->check($data,array_merge_recursive($this->updateRules,$this->rules))){
            $this->typeTrans($data);
            if(isset($data[$this->getPk()]))
                unset($data[$this->getPk()]);
            return true;
        }
        $error = $this->validateObj->getError();
        return false;
    }


}