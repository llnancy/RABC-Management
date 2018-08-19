<?php
/**
 * Created by PhpStorm.
 * User: LiLu
 * Date: 2018/8/7
 * Time: 19:33
 */

namespace app\admin\controller;

use app\admin\model\AdminMenu;
use app\admin\model\AdminRole;
use think\Controller;
use think\Request;

class BaseAdmin extends Controller{
    private static $now;
    private static $last;

    /**
     * 禁止未登录用户访问
     * 根据权限加载菜单
     * BaseAdmin constructor.
     * @param Request|null $request
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function __construct(Request $request = null){
        parent::__construct($request);
        // 未登录的用户不允许访问
        $this->login_admin = session('admin');
        if (!$this->login_admin){
            header('Location: /login');
            exit();
        }
        // 用户被禁用后应该退出
        if ($this->login_admin['status'] != '正常'){
            header('Location: /login');
            exit();
        }
        // 加载用户信息
        $admin_info['username'] = $this->login_admin['username'];
        $admin_info['true_name'] = $this->login_admin['true_name'];
        $admin_info['avatar'] = $this->login_admin['avatar'];
        $admin_info['add_time'] = $this->login_admin['add_time'];
        // 根据角色权限加载左侧菜单
        $admin_role = new AdminRole();
        $role = $admin_role->where('rid','EQ',$this->login_admin['rid'])->find();
        $menus = '暂无权限';
        $subMenusRouteNames = array();
        // 根据登录用户的rid能查询到角色
        if ($role){
            // 判断角色状态
            if ($role['status'] != '正常'){
                $menus = '已禁用';
            } else {
                if ($role['status'] == '正常' && $role['permissions']){
                    // 实例化AdminMenu模型
                    $admin_menu = new AdminMenu();
                    // 根据权限查询对应菜单（不能隐藏和禁用）
                    $where = 'mid in(' . implode(',',$role['permissions']) . ') and is_hidden=1 and status=1';
                    // 查询菜单并以mid为结果数组索引
                    $menus = $admin_menu->where($where)->column('mid,parent_id,menu_name,route_name','mid');
                    // 如果有菜单则生成菜单树，将子菜单存放到父级菜单新生成的submenu键中
                    $menus && $menus = self::getMenusTree($menus);
                    // 如果有菜单则获取子菜单路由名称
                    $menus && $subMenusRouteNames = self::getSubMenusRoutes($menus);
                }
            }
        }
        // 输出数据到模板
        $this->assign(['admin_info' => $admin_info,'subMenusRouteNames' => $subMenusRouteNames,'menus' => $menus]);
    }

    /**
     * 得到子菜单树,submenu键对应的值为子菜单
     * @param $menus
     * @return array
     */
    protected static function getMenusTree($menus){
        $menusTree = array();
        // 传入的菜单$menus是以mid为索引的
        foreach ($menus as $value) {
            // 根据parent_id是否为0判断子菜单和一级菜单
            if ($value['parent_id'] != 0){
                // 如果parent_id不等于0,则为子菜单,将当前子菜单的引用赋给父级菜单的submenu键
                $menus[$value['parent_id']]['submenu'][] = &$menus[$value['mid']];
            } else {
                // 为一级菜单,将当前一级菜单的引用赋给变量menusTree
                $menusTree[] = &$menus[$value['mid']];
            }
        }
        return $menusTree;
    }

    /**
     * 得到子菜单路由
     * @param $menus
     * @return array
     */
    protected static function getSubMenusRoutes($menus){
        $subMenusRoutes = array();
        foreach ($menus as $value) {
            if (count($value) == 5){
                foreach ($value['submenu'] as $v) {
                    $subMenusRoutes[] = &$v['route_name'];
                }
            }
        }
        return $subMenusRoutes;
    }

    /**
     * 得到数组的深度
     * @param $array
     * @return int
     */
    protected static function array_depth($array) {
        if(!is_array($array)) return 0;
        $max_depth = 1;
        foreach ($array as $value) {
            if (is_array($value)) {
                $depth = self::array_depth($value) + 1;
                if ($depth > $max_depth) {
                    $max_depth = $depth;
                }
            }
        }
        return $max_depth;
    }


    protected static function preventFastClick(){
        self::$now = time();
        if (self::$last = null){
            self::$last = time();
            return true;
        }
        if (self::$now - self::$last > 5000){
            self::$last = time();
            return true;
        } else {
            return false;
        }
    }
}