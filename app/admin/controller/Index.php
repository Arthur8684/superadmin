<?php
namespace app\admin\controller;

use think\util\AdminParent;

class Index extends AdminParent
{
    //后台菜单
    public function header()
    {
        return $this->fetch('header');
    }
}