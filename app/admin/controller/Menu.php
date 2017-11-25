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
    public function index()
    {
        $menu = Db::name('menu')->where('parent_id',0)->select();
        $this->assign('menu',$menu);
        return $this->fetch();
    }
    public function menuAdd(){
        //添加菜单
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
}