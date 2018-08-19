<?php
/**
 * Created by PhpStorm.
 * User: LiLu
 * Date: 2018/8/18
 * Time: 19:24
 */

namespace app\admin\model;

use think\Model;

class Admin extends Model {
    // 添加时间输出格式
    protected $dateFormat = 'Y年m月d日 H:i:s';
    protected $type = ['add_time' => 'timestamp'];
    // 开启自动写入时间戳
    protected $autoWriteTimestamp = true;
    // 定义添加时间字段名
    protected $createTime = 'add_time';
    // 关闭更新时间自动写入
    protected $updateTime = false;

    /**
     * 获取器，获取status字段值后自动处理
     * @param $value
     * @return mixed
     */
    public function getStatusAttr($value){
        $status = [1 => '正常',0 => '<jy style="color: #ff0000;">禁用</jy>'];
        return $status[$value];
    }

    /**
     * 一对一关联查询
     * @return \think\model\relation\HasOne
     */
    public function adminRole(){
        // 第一个参数：被关联的模型名称
        // 第二个参数：要关联表的关联字段
        // 第三个参数：这个数据表的关联字段
        return $this->hasOne('AdminRole','rid','rid')->field('name');
    }
}