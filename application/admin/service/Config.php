<?php
// +----------------------------------------------------------------------
// | @date: 2017/11/22 - 11:39
// +----------------------------------------------------------------------
// | @author：Ulex
// +----------------------------------------------------------------------
// | @desc：
// +----------------------------------------------------------------------
namespace app\admin\service;


use \extDB\DbModel;

class Config extends Base{


    public function __construct()
    {
        $this->model = \think\Loader::model('Config','model');
        $this->tableName = $this->model->getTable();
        $this->pk = $this->model->getPk();
        $this->dbObj = new DbModel($this->tableName);

    }


    /**
     * 返回多条数据
     * @param string $where
     */
    public function lists($where='',$page=1,$pagesize=10,$bind=array()){
        return array();
    }

    public function row($id=1){
        $data = $this->dbObj->getObj();
        return $data;
    }

    public function add($data){
        return false;
    }



    /**
     * @method post
     * @param $id
     * @param $data
     * @return mixed
     */
    public function edit($id,$data){
        $id = 1;
        if($this->model->checkUpdate($data,$this->errors)) {//验证通过
            $this->dbObj->where(array('id' => $id))->data($data)->update();
            return $this->getSuccInfo();
        }

        if($this->errors){
            $this->dbObj->rollBack();
            return $this->getSuccInfo(0,$this->errors);
        }

    }

    public function del($id){

    }





}