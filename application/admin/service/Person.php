<?php
// +----------------------------------------------------------------------
// | @date: 2017/11/22 - 11:39
// +----------------------------------------------------------------------
// | @author：Ulex
// +----------------------------------------------------------------------
// | @desc：
// +----------------------------------------------------------------------
namespace app\admin\service;

use app\admin\model\Base;
use think\Validate;
use \extDB\DbModel;
use think\Session;
class Person extends Base{



    protected $errors = '';


    protected $dbObj = null;
    protected $model = null;
    protected $tableName = '';

    public function __construct()
    {
        $this->model = \think\Loader::model('Person','model');
        $this->tableName = $this->model->getTable();
        $this->dbObj = new DbModel($this->tableName);
    }


    /**
     * 返回多条数据
     * @param array $where
     */
    public function lists($page=1,$where=''){
        $query = new \extDB\DbQuery($this->tableName);
        $query->page = $page;
        $query->pagesize = 10;
        $whereStr = 'del=0';
        if($where){
             $whereStr .= $where;
        }
        $query->where = $whereStr;
        $data = $query->find();
        $pageData = $query->getPageData();
         return array('data'=>$data,'page'=>$pageData);
    }

    public function data($id){
         return $this->dbObj->where(array('id'=>$id))->getObj();
    }

    /**
     * @method put
     * @param $data
     * @return array
     */
    public function add($data){
         $name = isset($data['name']) ? $data['name'] : '';
         $mobile = isset($data['mobile']) ? $data['mobile'] : '';
         if($name && $mobile){
             $this->dbObj->beginTrans();

             if($this->model->check($data,$this->errors)) {//验证通过
                 $num = $this->dbObj->data($data)->add();

                 if ($num > 0) {
                     $this->dbObj->commit();
                     return $this->getSuccInfo();
                 } else {
                     $this->errors = '添加失败';
                 }
             }
         }else{
             $this->errors = '姓名和手机号不能为空';
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
    public function delete($id){
        $id = intval($id);
         if($id<=0){
             $this->errors = '人员不存在';
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
        $id = intval($id);
        if($id<=0){
            $this->errors = '管理员不存在';
        }
        if(isset($data['id']))
            unset($data['id']);
        $update = $data;
        try{
            $this->dbObj->where(array('id'=>$id))->data($update)->update();
        }catch(\Exception $e){
            return $this->getSuccInfo(0,$e->getMessage());
        }

        return $this->getSuccInfo();

    }



}