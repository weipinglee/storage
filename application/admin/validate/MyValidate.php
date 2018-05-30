<?php
/**
 * Created by PhpStorm.
 * User: weipinglee
 * Date: 2018/5/30
 * Time: 11:31
 */

namespace app\admin\validate;

use think\Validate;
use extDB\DbModel;

class MyValidate extends Validate
{

    /**
     * 验证是否唯一,注意与tp5原验证规则做了修改
     * @access protected
     * @param mixed     $value  字段值
     * @param mixed     $rule  验证规则 格式：数据表,主键名(排除主键为$data[主键]的行),验证的条件（比如，status=1)
     * @param array     $data  数据
     * @param string    $field  验证字段名
     * @return bool
     */
    protected function unique($value, $rule, $data, $field)
    {
        if (is_string($rule)) {
            $rule = explode(',', $rule);
        }
        if (false !== strpos($rule[0], '\\')) {
            // 指定模型类
            $db = new $rule[0];
        } else {
             $db = new DbModel($rule[0]);

        }
        $key = isset($rule[2]) ? $rule[2] : $field;

        if (strpos($key, '^')) {
            // 支持多个字段验证
            $fields = explode('^', $key);
            foreach ($fields as $key) {
                $map[$key] = $data[$key];
            }
        } elseif (strpos($key, '=')) {
            parse_str($key, $map);
        } else {
            $map[$key] = $data[$field];
        }

        $pk = isset($rule[1]) ? $rule[1] : 'id';
        if (is_string($pk)) {
            if (isset($data[$pk])) {
                $map[$pk] = ['neq', $data[$pk]];
            }
        }
        if ($db->where($map)->fields($pk)->getObj()) {
            return false;
        }
        return true;
    }

}