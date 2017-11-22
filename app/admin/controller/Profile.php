<?php
/**
 * Created by PhpStorm.
 * User: rj158
 * Date: 2017/11/22
 * Time: 16:11
 */
namespace app\admin\controller;

use think\util\AdminParent;

class Profile extends AdminParent{
    //个人资料
    public function index(){
        return $this->fetch();
    }
}