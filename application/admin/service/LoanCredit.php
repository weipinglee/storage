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

class LoanCredit extends Base{

    private $queryObj = null;
    public function __construct()
    {
        $this->model = \think\Loader::model('LoanCredit','model');
        $this->tableName = $this->model->getTable();
        $this->pk = $this->model->getPk();
        $this->dbObj = new DbModel($this->tableName);

    }


    /**
     * 返回多条数据
     * @param string $where
     */
    public function lists($where='',$page=1,$pagesize=10,$bind=array()){
        $query = new \extDB\DbQuery($this->tableName . ' as l ');
        $query->join = 'left join person as p1 on l.owner=p1.id  ';
        $query->fields = 'l.*,p1.name';
        if($page>0){
            $query->page = $page;
            $query->pagesize = $pagesize!='' ? $pagesize : 10;
        }
        $query->where = $where ;
        $query->bind = $bind;
        $query->order = 'l.id desc';
        $data = $query->find();
        $this->queryObj = $query;
        return $data;
    }

    public function pageBar(){
        return $this->queryObj->getPageData();
    }

    public function row($id,$where=array()){
        $id=intval($id);
        if($id<=0){
            return false;
        }
        $where['l.id'] = $id;
        $query = new \extDB\DbQuery($this->tableName . ' as l ');
        $query->join = 'left join person as p1 on l.person_id=p1.id 
                        left join person as p2 on l.rec_person_id=p2.id ';
        $query->fields = 'l.*,p1.id as owner,p1.name ,p1.mobile,p1.shenfenzheng,p2.name as rec_name';
         $query->where = $where;
         return $query->getObj();
    }

    /**
     * @method put
     * @param $data
     * @return array
     */
    public function add($data){
         $this->dbObj->beginTrans();
         if($this->model->checkInsert($data,$this->errors)){//验证通过
             $num = $this->dbObj->data($data)->add();

             if($num>0){
                 $this->dbObj->commit();
                 return $this->getSuccInfo();
             }else{
                 $this->errors = '添加失败';
             }
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
             $this->errors = '不存在';
         }
         $id = intval($id);

         if($this->errors){
             return $this->getSuccInfo(0,$this->errors);
         }
         $where['id'] = $id;
          $this->dbObj->where($where)->data(array('del'=>1))->update();
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
            $this->errors = '该贷款不存在';
        }
        $id = intval($id);
        if($this->model->checkUpdate($data,$this->errors)) {//验证通过
            $where['id'] = $id;
            $this->dbObj->where($where)->data($data)->update();
            return $this->getSuccInfo();
        }

        if($this->errors){
            $this->dbObj->rollBack();
            return $this->getSuccInfo(0,$this->errors);
        }

    }





}