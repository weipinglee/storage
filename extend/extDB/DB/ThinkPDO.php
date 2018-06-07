<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/2/14 0014
 * Time: 上午 11:43
 */

namespace extDB\DB;
use \think\Db;

class ThinkPDO {

    static private $rollback = false;//是否应该回滚的标记
    //私有克隆
    private function __clone() {}


    /**
     * @brief 获取SQL语句的类型,类型：select,update,insert,delete
     * @param string $sql 执行的SQL语句
     * @return string SQL类型
     */
    private function getSqlType($sql)
    {
        $strArray = explode(' ',trim($sql),2);

        return strtoupper($strArray[0]);
    }


    /**
     * @brief 执行一条sql
     * @param string $sql 要执行的sql语句，以:占位符形式提供,如果绑定参数有形同名字的，使用str 的where条件，使用别名
     * @param array $data 绑定的参数数组
     * @param string $type sql类型
     * @return array | int
     */
    public function exec($sql,$data=array(),$type=''){
        $sql = ltrim($sql);
        if($type==''){
            $type = $this->getSqlType($sql);

        }

        try{
            if($type=='SELECT'){
                $res = Db::query($sql,$data);
                return $res;
            }else{
                switch($type){
                    case 'UPDATE' :
                    case 'DELETE' :
                    default:{
                        return Db::execute($sql,$data);
                    }
                    break;
                    case 'INSERT' :{
                        if(Db::execute($sql,$data)){
                            return Db::getLastInsID();
                        }

                    }
                }
            }

        }
        catch(\PDOException $e){
           // echo $e1->getMessage();
            //TODO:错误信息记录日志
            $this->setRollback();
           throw new \Exception($e->getMessage().'<<<sql:'.$sql);
        }
        catch(\Throwable $e){
            $this->setRollback();
            throw new \Exception($e->getMessage().'<<<sql:'.$sql);
        }
        catch(\Exception $e){
           // exit($e->getMessage());
            $this->setRollback();
            throw new \Exception($e->getMessage().'<<<sql:'.$sql);
        }


        return false;

    }

    /**
     * 返回上次新增条目id
     * @return [type] [description]
     */
    public function lastInsertId(){
        return Db::getLastInsID();
    }

    //开启事物
    public function beginTrans(){
        Db::startTrans();
    }
    //事物回滚(在事物中才回滚)
    public function rollBack(){
         Db::rollback();
         return true;
    }

    public function commit(){
        if(self::getRollback()===false){
            Db::commit();
            return true;
        }
        else{
            Db::rollback();
            return false;
        }



    }

    private function setRollback(){
        self::$rollback = true;
    }
    //是否应该回滚
    private function getRollback(){
        return self::$rollback;
    }





}