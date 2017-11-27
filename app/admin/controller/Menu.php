<?php
/**
 * Created by PhpStorm.
 * User: rj158
 * Date: 2017/11/23
 * Time: 14:38
 * 菜单管理
 */
namespace app\admin\controller;

use think\util\AdminParent;
use think\Loader;
use think\Db;

class Menu extends AdminParent{
    /**
     * @return false|mixed|\PDOStatement|string|\think\Collection
     * 获取菜单
     */
    private function getMenu()
    {
        return Db::name('menu')->where('status',1)->where('parent_id',0)->select();
    }
    //菜单列表
    public function index()
    {
        $menu_id = $this->request->param() ? $this->request->param()['menu_id'] : null;
        if(empty($menu_id))
        {
            $where = ['parent_id'=>0];
        }
        else
        {
            $where = ['parent_id'=>$menu_id];
            $this->assign('menu_id',$menu_id);
        }
        $menu = Db::name('menu')->where($where)->order('sort')->select();
        foreach($menu as &$v)
        {
            if($v['parent_id'] == 0)
            {
                $v['parent_menu'] = '顶级菜单';
            }
            else
            {
                $v['parent_menu'] = Db::name('menu')->field('menu_title')->where('menu_id',$v['parent_id'])->find()['menu_title'];
            }
            $v['child_len'] = Db::name('menu')->where('parent_id',$v['menu_id'])->count();
        }
        unset($v);
        $this->assign('menu',$menu);
        return $this->fetch();
    }
    //添加菜单
    public function menuAdd(){
        $menu_id = null;
        if($this->request->isGet())
        {
            $menu_id = $this->request->param() ? $this->request->param()['menu_id'] : null;
        }
        if($this->request->isPost())
        {
            $data = $this->request->post();
            $validate = Loader::validate('Menu');
            if(!$validate->check($data))
            {
                $this->error($validate->getError());
            }
            $data['create_time'] = time();
            $add = Db::name('menu')->insert($data);
            if(!empty($add))
            {
                $this->success('菜单添加成功','Menu/index');
            }
            else
            {
                $this->error('菜单添加失败');
            }
        }

        $this->assign('menu_id',$menu_id);
        $this->assign('menu',$this->getMenu());
        return $this->fetch();
    }
    //删除菜单
    public function delMenu(){
        $menu_id = $this->request->param() ? $this->request->param()['menu_id'] : null;
        if(empty($menu_id))
        {
           $this->error('非法操作');
        }
        $del = Db::name('menu')->where('menu_id',$menu_id)->delete();
        if(!empty($del))
        {
            $this->success('删除成功',url('admin/Menu/index'));
        }
        else
        {
            $this->error('删除失败');
        }
    }
    //修改菜单
    public function menuEdit(){
        $menu_id = null;

        if($this->request->isGet())
        {
            $id = $this->request->param() ? $this->request->param()['menu_id'] : null;
            if(empty($id))
            {
                $this->error('非法操作');
            }
            $info = Db::name('menu')->where('menu_id',$id)->find();       //菜单信息
            $menu_id = Db::name('menu')->where('menu_id',$info['parent_id'])->field('menu_id')->find()['menu_id'];      //上级菜单id
            $this->assign('info',$info);
        }
        if($this->request->isPost())
        {
            $data = $this->request->param();
            $validate = Loader::validate('Menu');
            if(!$validate->check($data))
            {
                $this->error($validate->getError());
            }
            $edit = Db::name('menu')->where('menu_id',$data['menu_id'])->update($data);
            if(!empty($edit))
            {
                $this->success('修改成功','admin/Menu/index');
            }
            else
            {
                $this->error('修改失败');
            }

        }
        $this->assign('menu_id',$menu_id);
        $this->assign('menu',$this->getMenu());
        return $this->fetch('menuEdit');
    }
}