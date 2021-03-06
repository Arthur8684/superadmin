<?php
/**
 * Created by PhpStorm.
 * User: rj158
 * Date: 2017/11/27
 * Time: 16:00
 * 权限管理
 */
namespace app\admin\controller;

use think\Db;
use think\Loader;
use think\util\AdminParent;

class Auth extends AdminParent{
    //菜单列表
    private function getMenu()
    {
        $menu = Db::name('menu')->where('status',1)->where('parent_id',0)->select();
        foreach($menu as &$v)
        {
            $v['child_menu'] = Db::name('menu')->where('status',1)->where('parent_id',$v['menu_id'])->select();
        }
        unset($v);
        return $menu;
    }
    //权限组列表
    public function index(){
        $auth_list = Db::name('auth')->where('status',1)->select();
        $this->assign('auth_list',$auth_list);
        return $this->fetch();
    }
    //添加权限组
    public function addAuthGroup(){
        if($this->request->isPost())
        {
            $data = $this->request->param();
            $validate = Loader::validate('Auth');
            if(!$validate->check($data))
            {
                $this->error($validate->getError());
            }
            $add_data['auth_title'] = $data['auth_title'];
            unset($data['auth_title']);
            $menu_auth_id = implode(',',$data);
            if(!empty($menu_auth_id))
            {
                $add_data['menu_auth_id'] = $menu_auth_id;
            }
            else
            {
                $this->error('请选择该权限组菜单');
            }
            $add_data['create_time'] = time();
            $add = Db::name('auth')->insert($add_data);
            if(!empty($add))
            {
                $this->success('权限组添加成功',url('admin/Auth/index'));
            }
            else
            {
                $this->error('权限组添加失败');
            }
        }
        $this->assign('menu',$this->getMenu());
        return $this->fetch('add_auth_group');
    }
    //删除权限组
    public function delAuthGroup(){
        $auth_id = $this->request->param() ? $this->request->param()['auth_id'] : null;
        if(empty($auth_id))
        {
            $this->error('非法操作');
        }
        $del = Db::name('auth')->where('auth_id',$auth_id)->delete();
        if(!empty($del))
        {
            $this->success('删除成功');
        }
        else
        {
            $this->error('删除失败');
        }
    }
    //修改权限组
    public function editAuthGroup()
    {
        if($this->request->isGet())
        {
            $auth_id = $this->request->param() ? $this->request->param()['auth_id'] : null;
            if(empty($auth_id))
            {
                $this->error('非法操作');
            }
            $auth_info = Db::name('auth')->where('status',1)->where('auth_id',$auth_id)->find();
            if(stripos($auth_info['menu_auth_id'],',') !== false)
            {
                $auth_info['menu_auth_id'] = explode(',',$auth_info['menu_auth_id']);
            }
            else
            {
                $auth_info['menu_auth_id'] = [$auth_info['menu_auth_id']];
            }
            $this->assign('auth_id',$auth_id);
            $this->assign('auth_info',$auth_info);
        }
        if($this->request->isPost())
        {
            $data = $this->request->param();
            $validate = Loader::validate('Auth');
            if(!$validate->check($data))
            {
                $this->error($validate->getError());
            }
            $edit_data['auth_title'] = $data['auth_title'];
            $edit_data['auth_id'] = $data['auth_id'];
            unset($data['auth_title']);
            unset($data['auth_id']);
            $menu_auth_id = implode(',',$data);
            if(empty($menu_auth_id))
            {
                $this->error('请选择该权限组菜单');
            }
            $edit_data['menu_auth_id'] = $menu_auth_id;
            $edit = Db::name('auth')->update($edit_data);
            if(!empty($edit))
            {
                $this->success('修改成功');
            }
            else
            {
                $this->error('修改失败');
            }
        }
        $this->assign('menu',$this->getMenu());
        return $this->fetch('edit_auth_group');
    }
}