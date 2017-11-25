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
        }
        $menu = Db::name('menu')->where($where)->select();
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
        if($this->request->isPost())
        {
            $data = $this->request->post();
            $validate = Loader::validate('Menu');
            if(!$validate->check($data))
            {
                $this->error($validate->getError());
            }
            else
            {
                $data['create_time'] = time();
                $add = Db::name('menu')->insert($data);
                if(!empty($add))
                {
                    $this->error('菜单添加成功');
                }
                else
                {
                    $this->error('菜单添加失败');
                }
            }
        }
        //菜单列表
        $menu = Db::name('menu')->where('status',1)->where('parent_id',0)->select();
        $this->assign('menu',$menu);
        return $this->fetch();
    }
    public function delMenu(){
        $menu_id = $this->request->param() ? $this->request->param()['menu_id'] : null;
        if(empty($menu_id))
        {
           $this->error('非法操作');
        }
        $del = Db::name('menu')->where('menu_id',$menu_id)->delete();
        if(!empty($del))
        {
            $this->success('删除成功');
        }
        else
        {
            $this->error('删除失败');
        }
    }
}