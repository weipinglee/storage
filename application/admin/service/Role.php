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
use \extDB\DbQuery;
class Role extends Base{


    public function __construct()
    {
        $this->model = \think\Loader::model('Role');
        $this->tableName = $this->model->getTable();
        $this->pk = $this->model->getPk();
        $this->dbObj = new DbModel($this->tableName);

    }


    /**
     * 返回多条数据
     * @param string $where
     */
    public function lists($where='',$page=1,$pagesize=10,$bind=array()){
        $query = new DbQuery($this->tableName . ' as a');
        $query->join = 'left join role_pri as b on a.id=b.role_id
			        left join privilege as c on c.id =b.pri_id';
        $query->fields = 'a.*,GROUP_CONCAT(c.pri_name) pri_name';
        $query->group = ' a.id';
        $query->page = $page;
        $query->pagesize = $pagesize;
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
            $this->errors = '不存在';
        }
        $id = intval($id);
        $update = $data;

        if(empty($update)){
            return $this->getSuccInfo(0,'更新字段为空');
        }
         $this->dbObj->where(array('id'=>$id))->data($update)->update();
        return $this->getSuccInfo();

    }



}