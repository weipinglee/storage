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
use think\Db;

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
    public function lists($where='',$page=1,$pagesize=10,$bind=array()){
        $query = new \extDB\DbQuery($this->tableName);
        $whereStr = 'del=0';
        $query->page = $page;
        $query->pagesize = 10;
        $whereStr .= $where!='' ? ' AND '.$where : '';
        $query->where = $whereStr ;
        $query->bind = $bind;
        $data = $query->find();
        $pageData = $query->getPageData();
         return array('data'=>$data,'page'=>$pageData);
    }

    public function row($id,$where=array()){
         $data = $this->dbObj->where(array('id'=>$id))->getObj();
        $data['role_id'] = 0;
         if(!empty($data)){
             //获取管理员角色
             $adminRole = new DbModel('admin_role');
             $data['role_id'] = $adminRole->where(array('admin_id'=>$data['id']))->getField('role_id');
         }
         return $data;

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
                 $role_id = isset($data['role_id'])?$data['role_id']:0;
                 unset($data['role_id']);
                 $data['password'] = md5($data['password']);
                 $num = $this->dbObj->data($data)->add();

                 if($num>0){
                     $adminRole = new DbModel('admin_role');
                     $adminRole->data(array('admin_id'=>$num,'role_id'=>$role_id))->add();
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
    public function del($id,$where=array()){
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
    public function edit($id,$data,$where=array()){
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

        $this->dbObj->beginTrans();
         $this->dbObj->where(array('id'=>$id))->data($update)->update();
        if(isset($data['role_id'])){
            $adminRole = new DbModel('admin_role');
            $adminRole->where(array('admin_id'=>$id))->delete();
            $adminRole->data(array('admin_id'=>$id,'role_id'=>$data['role_id']))->add();
        }

        $this->dbObj->commit();

        return $this->getSuccInfo();

    }



}