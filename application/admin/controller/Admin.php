<?php
/**
 * Created by PhpStorm.
 * User: LiLu
 * Date: 2018/8/8
 * Time: 22:04
 */

namespace app\admin\controller;

use app\admin\model\AdminRole;
use think\Request;
use app\admin\model\Admin as AdminModel;

class Admin extends BaseAdmin {
    // Admin 模型实例
    protected $adminModel = null;
    // AdminRole模型实例
    protected $adminRole = null;

    /**
     * 构造函数，初始化Admin模型
     * Admin constructor.
     * @param Request|null $request
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function __construct(Request $request = null) {
        parent::__construct($request);
        // 实例化Admin模型
        $this->adminModel = new AdminModel();
    }

    /**
     * 渲染管理员列表视图
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function admin_list(){
        // 查询管理员信息
        $admins = $this->adminModel->field('id,username,true_name,add_time,rid,status')->select();
        $this->assign('admins',$admins);
        return $this->fetch();
    }

    /**
     * 添加或编辑管理员
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function admin_edit(){
        // 管理员id,添加时未传入为空,强转后为0
        // 接收可选路由变量id
        $id = (int)($this->request->route())['id'];
        // 初始化空数组,编辑时才进行查询
        $data['admin'] = array('id'=>'','username'=>'','true_name'=>'','adminRole'=>['name' => ''],'status'=>'');
        if ($id > 0){
            // 查询当前管理员信息
            $data['admin'] = $this->adminModel->field('id,username,true_name,rid,status')->where(['id' => $id])->find();
        }
        // 实例化AdminRole模型
        $this->adminRole = new AdminRole();
        // 查询所有角色信息
        $data['roles'] = $this->adminRole->field('rid,name')->select();
        $this->assign('data',$data);
        return $this->fetch();
    }

    /**
     * 保存管理员信息
     */
    public function save(){
        // 编辑时结束管理员id,空值强转后为0
        $id = (int)trim(input('post.id'));
        // 接收表单数据
        // 使用htmlentities函数转义HTML标签
        $adminInfo['username'] = htmlentities(trim(input('post.username')));
        $password = htmlentities(trim(input('post.password')));
        $adminInfo['true_name'] = htmlentities(trim(input('post.true_name')));
        $adminInfo['rid'] = (int)trim(input('post.rid'));
        // 如果checkbox未选中,则值为空,int强转后为0
        $adminInfo['status'] = (int)trim(input('post.status'));
        // 非空验证 空取反为true
        if (!$adminInfo['username']){
            exit(json_encode(array('code'=>4001,'msg'=>'用户名不能为空')));
        }
        if ($id == 0 && !$password){
            exit(json_encode(array('code'=>4002,'msg'=>'密码不能为空')));
        }
        if (!$adminInfo['true_name']){
            exit(json_encode(array('code'=>4005,'msg'=>'真实姓名不能为空')));
        }
        if (!$adminInfo['rid'] || $adminInfo['rid'] == 0){
            exit(json_encode(array('code'=>4006,'msg'=>'请选择角色')));
        }
        if ($adminInfo['status'] != 0 && $adminInfo['status'] != 1){
            exit(json_encode(array('code'=>4007,'msg'=>'非法状态')));
        }
        // 加密密码 编辑管理员时未输入密码则不进行加密
        if ($password){
            $adminInfo['password'] = md5($adminInfo['username'] . $password);
        }
        if ($id == 0){
            // 添加管理员
            // 校验管理员是否已存在
            $admin = $this->adminModel->field('username')->where(['username' => $adminInfo['username']])->find();
            if ($admin){
                exit(json_encode(array('code'=>6001,'msg'=>'该管理员已存在')));
            }
            // 添加时间 时间戳 模型中开启时间戳自动写入
            // $adminInfo['add_time'] = time();
            // 添加管理员
            $this->adminModel->data($adminInfo);
            $result = $this->adminModel->save();
            if (!$result){
                exit(json_encode(array('code'=>5004,'msg'=>'保存失败')));
            }
        } else {
            // 更新管理员信息
            $up = $this->adminModel->isUpdate(true)->save($adminInfo,['id'=>$id]);
            if (!$up){
                exit(json_encode(array('code'=>5004,'msg'=>'未作出任何修改')));
            }
        }
        exit(json_encode(array('code'=>2002,'msg'=>'保存成功')));
    }

    /**
     * 禁用管理员
     */
    public function admin_disable(){
        $id = (int)trim(input('post.id'));
        $info['status'] = 0;
        $result = $this->adminModel->save($info,['id'=>$id]);
        if (!$result){
            exit(json_encode(array('code'=>5005,'msg'=>'禁用失败')));
        }
        exit(json_encode(array('code'=>2003,'msg'=>'禁用成功')));
    }
}