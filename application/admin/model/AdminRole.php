<?php
/**
 * Created by PhpStorm.
 * User: LiLu
 * Date: 2018/8/18
 * Time: 19:28
 */

namespace app\admin\model;

use think\Model;

class AdminRole extends Model {
    protected $pk = 'rid';
    // 权限字段自动使用json格式写入和写出，使用save新增数据时失效
    protected $type = ['permissions' => 'array'];

    /**
     * 获取器，获取status字段值后自动处理
     * @param $value
     * @return mixed
     */
    public function getStatusAttr($value){
        $status = [1 => '正常',0 => '<jy style="color: #ff0000;">禁用</jy>'];
        return $status[$value];
    }
}