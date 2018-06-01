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

class Loan extends Base{


    public function __construct()
    {
        $this->model = \think\Loader::model('Loan','model');
        $this->tableName = $this->model->getTable();
        $this->pk = $this->model->getPk();
        $this->dbObj = new DbModel($this->tableName);

    }


    /**
     * 返回多条数据
     * @param string $where
     */
    public function lists($page=1,$where=''){
        $query = new \extDB\DbQuery($this->tableName . ' as l ');
        $query->join = 'left join person as p1 on l.person_id=p1.id 
                        left join person as p2 on l.rec_person=p2.id ';
        $query->fields = 'l.*,p1.name ,p1.mobile,p2.name as rec_person_name';
        $query->page = $page;
        $query->pagesize = 10;
        $query->where = 'l.del=0';
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
         $this->dbObj->beginTrans();
         if($this->model->checkInsert($data,$this->errors)){//验证通过
             if($data['begin_date']==''){
                 $data['begin_date'] = null;
             }
             if($data['end_date']==''){
                 $data['end_date'] = null;
             }
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
    public function del($id){
         if(intval($id)<=0){
             $this->errors = '不存在';
         }
         $id = intval($id);

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

         $this->dbObj->where(array('id'=>$id))->data($data)->update();
        return $this->getSuccInfo();

    }





}