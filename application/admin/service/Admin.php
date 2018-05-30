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

class Admin extends Base{


    public function __construct()
    {
        $this->model = \think\Loader::model('Admin','model');
        $this->tableName = $this->model->getTable();
        $this->pk = $this->model->getPk();
        $this->dbObj = new DbModel($this->tableName);

    }


    /**
     * 返回多条数据
     * @param string $where
     */
    public function lists($page=1,$where=''){
        $query = new \extDB\DbQuery($this->tableName);
        $query->page = $page;
        $query->pagesize = 10;
        $query->where = 'del=0';
        $data = $query->find();
        $pageData = $query->getPageData();
         return array('data'=>$data,'page'=>$pageData);
    }

    public function row($id){
         return $this->dbObj->where(array('id'=>$id))->getObj();
    }

    /**
     * @method put
     * @param $data
     * @return array
     */
    public function add($data){
         $name = isset($data['adminname']) ? $data['adminname'] : '';
         $pass = isset($data['password']) ? $data['password'] : '';
         if($name && $pass){
             $this->dbObj->beginTrans();
             if($this->model->checkInsert($data,$this->errors)){//验证通过
                 $data['password'] = md5($data['password']);
                 $num = $this->dbObj->data($data)->add();

                 if($num>0){
                     $this->dbObj->commit();
                     return $this->getSuccInfo();
                 }else{
                     $this->errors = '添加失败';
                 }
             }
         }else{
             $this->errors = '用户名和密码不能为空';
         }

         if($this->errors){
             $this->dbObj->rollBack();
             return $this->getSuccInfo(0,$this->errors);
         }

    }

    /**
     * @method delete
     * @param int $id
     * @return mixed
     */
    public function del($id){
         if(intval($id)<=0){
             $this->errors = '管理员不存在';
         }
         $id = intval($id);
         if($id==1){
             $this->errors = '超级管理员不能删除';
         }
         if($this->errors){
             return $this->getSuccInfo(0,$this->errors);
         }
          $this->dbObj->where(array('id'=>$id))->data(array('del'=>1))->update();
         return $this->getSuccInfo();

    }

    /**
     * @method post
     * @param $id
     * @param $data
     * @return mixed
     */
    public function edit($id,$data){
        if(intval($id)<=0){
            $this->errors = '管理员不存在';
        }
        $id = intval($id);
        $update = array();
        if(isset($data['adminname'])){
            $update['adminname'] = $data['adminname'];
        }
        if(isset($data['password'])){
            $update['password'] = md5($data['password']);
        }
        if(empty($update)){
            return $this->getSuccInfo(0,'更新字段为空');
        }
         $this->dbObj->where(array('id'=>$id))->data($update)->update();
        return $this->getSuccInfo();

    }



}