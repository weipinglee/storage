<?php
// +----------------------------------------------------------------------
// | @date: 2017/11/22 - 11:39
// +----------------------------------------------------------------------
// | @author：Ulex
// +----------------------------------------------------------------------
// | @desc：
// +----------------------------------------------------------------------
namespace app\admin\service;

use think\Validate;
use \extDB\DbModel;
use think\Loader;
class PersonBank extends Base{



    protected $personTableName = '';

    public function __construct()
    {

        $this->model = Loader::model('PersonBank','model');
        $this->tableName = $this->model->getTable();
        $this->pk = $this->model->getPk();
        $this->dbObj = new DbModel($this->tableName);
        $personModel = Loader::model('Person','model');
        $this->personTableName = $personModel->getTable();

    }


    /**
     * 返回多条数据
     * @param array $where
     * @param array $bind where条件绑定参数
     */
    public function lists($page=1,$where='',$bind=array()){
        $query = new \extDB\DbQuery($this->tableName . ' as b');
        $query->join = ' left join '.$this->personTableName.' as p on b.person_id=p.id';
        $query->fields = 'b.*,p.name,p.mobile';
        $query->page = $page;
        $query->pagesize = 10;
        $whereStr = 'b.del=0';
        if($where){
             $whereStr .= $where;
        }
        $query->where = $whereStr;
        $query->bind = $bind;
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
         $bank_name = isset($data['bank_name']) ? $data['bank_name'] : '';
         $bank_acc = isset($data['bank_acc']) ? $data['bank_acc'] : '';
         $person_id = isset($data['person_id']) ? $data['person_id'] : 0;
         if($bank_name && $bank_acc && $person_id){
             $this->dbObj->beginTrans();

             if($this->model->checkInsert($data,$this->errors)) {//验证通过
                 $num = $this->dbObj->data($data)->add();

                 if ($num > 0) {
                     $this->dbObj->commit();
                     return $this->getSuccInfo();
                 } else {
                     $this->errors = '添加失败';
                 }
             }
         }else{
             $this->errors = '银行名称和银行账户不能为空';
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