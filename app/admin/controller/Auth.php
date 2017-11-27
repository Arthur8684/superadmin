<?php
/**
 * Created by PhpStorm.
 * User: rj158
 * Date: 2017/11/27
 * Time: 16:00
 * 权限管理
 */
namespace app\admin\controller;

use think\util\AdminParent;
use think\Db;

class Auth extends AdminParent{
    public function index(){
        return $this->fetch();
    }
    public function addAuthGroup(){
        if($this->request->isPost())
        {
            dump($this->request->param());
            exit;
        }
        $menu = Db::name('menu')->where('status',1)->select();
        $this->assign('menu',$menu);
        return $this->fetch();
    }
}