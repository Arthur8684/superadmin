<?php
/**
 * Created by PhpStorm.
 * User: rj158
 * Date: 2017/11/16
 * Time: 14:01
 */
namespace app\admin\controller;

use think\util\AdminParent;

class FileManager extends AdminParent{
    public function index()
    {
        return $this->fetch();
    }
}