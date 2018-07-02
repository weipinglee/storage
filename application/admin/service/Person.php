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
use Overtrue\Pinyin\Pinyin;
class Person extends Base{



    public function __construct()
    {
        $this->model = \think\Loader::model('Person','model');
        $this->tableName = $this->model->getTable();
        $this->pk = $this->model->getPk();
        $this->dbObj = new DbModel($this->tableName);
    }


    /**
     * 返回多条数据
     * @param int $page
     * @param array|string $where
     */
    public function lists($where='',$page=1,$pagesize=10,$bind=array()){
        $query = new \extDB\DbQuery($this->tableName);
        $query->page = $page;
        $query->pagesize = $pagesize;
        $whereStr = $where;
        $query->where = $whereStr ;
        $query->bind = $bind;
        $data = $query->find();

        $pageData = $query->getPageData();
        return array('data'=>$data,'page'=>$pageData);


    }

    public function row($id,$where=array()){
        $where['id'] = $id;
         return $this->dbObj->where($where)->getObj();
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

             if($this->model->checkInsert($data,$this->errors)) {//验证通过
                 $data['pinyin'] = $this->getPinyin($data['name']);
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
     * 获取$name的首字母
     * @param $name
     * @return string
     */
    private function getPinyin($name){
        $pinyin = new Pinyin();
        return $pinyin->abbr($name);
    }

    /**
     * @method delete
     * @param int $id
     * @return mixed
     */
    public function del($id,$where=array()){
        $id = intval($id);
         if($id<=0){
             $this->errors = '人员不存在';
         }

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
        $id = intval($id);
        if($id<=0){
            $this->errors = '管理员不存在';
        }
        if(isset($data['id']))
            unset($data['id']);
        $update = $data;
        if($this->model->checkUpdate($data,$this->errors)) {//验证通过
            try{
                $where['id'] = $id;
                $update['pinyin'] = $this->getPinyin($update['name']);
                $this->dbObj->where($where)->data($update)->update();
            }catch(\Exception $e){
                return $this->getSuccInfo(0,$e->getMessage());
            }
        }else{
            return $this->getSuccInfo(0,$this->errors);
        }


        return $this->getSuccInfo();

    }



}