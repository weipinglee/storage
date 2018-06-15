<?php
/**
 * Created by PhpStorm.
 * User: weipinglee
 * Date: 2018/6/2
 * Time: 9:50
 */

namespace extDB\DB;


class WhereHandle
{


    protected $bind = array();

    /**
     *
     * @param array|string $where
     * @return array 第一个元素是sql条件语句，第二个是绑定数组
     */
    public function handle($where){
        if(is_string($where)){
            return array($where,array());
        }elseif(is_array($where) && !empty($where)){
            $whereStr = ' ';
            foreach($where as $key=>$val){
                if(!is_array($val)){
                    $whereStr .= call_user_func(array($this,'eq'),$key,$val). ' AND ';
                }elseif(is_array($val)){     //比如where为：array($key=>array('eq',3))
                    $action = '';
                    $value = '';
                    foreach($val as $k=>$oper){
                        if(is_array($oper) && isset($oper[0]) && isset($oper[1])){
                           $action = $oper[0];
                           $value = $oper[1];
                        }
                        elseif(is_string($oper)){
                            if($action=='')
                                $action = $oper;
                            else{
                                $value = $oper;
                            }


                        }
                        if($action && $value!== ''){
                            $whereStr .= call_user_func(array($this,$action),$key,$value). ' AND ';
                            $action='';
                            unset($value);
                        }

                    }


                }
            }
            $whereStr = substr($whereStr,0,-4);
            return array($whereStr,$this->bind);
        }
        return array('',array());
    }

    /**
     * 相等的where条件
     * @param $key
     * @param $value
     */
    protected function eq($key,$value){
        $mark = str_replace('.','_',$key);
        $this->bind[$mark] = $value;
        return $key . '=:' .$mark;
    }

    protected function like($key,$value){
        if(is_integer($value) || is_float($value)){
            return $key . ' like %'.$value.'% ';
        }
        return $key . ' like "%'.$value.'%"';
    }

    protected function gt($key,$value){
        $mark = str_replace('.','_',$key).'_gt';
        $this->bind[$mark] = $value;
        return $key . '>:' .$mark;
    }

    /**
     * 大于等于
     * @param $key
     * @param $value
     */
    protected function egt($key,$value){
        $mark = str_replace('.','_',$key).'_egt';
        $this->bind[$mark] = $value;
        return $key . '>=:' .$mark;
    }

    protected function lt($key,$value){
        $mark = str_replace('.','_',$key).'_lt';
        $this->bind[$mark] = $value;
        return $key . '<:' .$mark;
    }

    protected function elt($key,$value){
        $mark = str_replace('.','_',$key).'_elt';
        $this->bind[$mark] = $value;
        return $key . '<=:' .$mark;
    }

    protected function neq($key,$value){
        $mark = str_replace('.','_',$key);
        $this->bind[$mark] = $value;
        return $key . '!=:' .$mark;
    }

    protected function in($key,$value){
        $mark = str_replace('.','_',$key);
        $in = '';
        $value = explode(',',$value);
        foreach($value as $k=>$v){
            $this->bind[$mark.'_'.$k] = $v;
            $in .= $in=='' ? ':'.$mark.'_'.$k : ',:'.$mark.'_'.$k;
        }
        return  $key . ' in ('.$in.') ';


    }

    protected function notin($key,$value){
        $mark = str_replace('.','_',$key);
        $in = '';
        $value = explode(',',$value);
        foreach($value as $k=>$v){
            $this->bind[$mark.'_'.$k] = $v;
            $in .= $in=='' ? ':'.$mark.'_'.$k : ',:'.$mark.'_'.$k;
        }
        return  $key . ' not in ('.$in.') ';
    }


}