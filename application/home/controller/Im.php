<?php
/**
 * Created by PhpStorm.
 * User: rj158
 * Date: 2017/12/28
 * Time: 10:23
 */
namespace app\home\controller;
use think\Controller;
class Im extends Controller{
    public function index(){
        return $this->fetch();
    }
}