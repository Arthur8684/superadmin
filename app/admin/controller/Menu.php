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

class Menu extends AdminParent{
    public function index()
    {
        return $this->fetch();
    }
    public function menuAdd(){
        if($this->request->isPost())
        {
            $this->error('123');
        }
        return $this->fetch();
    }
}