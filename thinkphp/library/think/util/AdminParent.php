<?php
namespace think\util;

use think\Controller;
use think\Request;

/**
 * Admin控制器基类 抽象类
 */

class AdminParent extends Controller
{
    protected $request;
    public function __construct()
    {
        parent::__construct();
        $this->IsAdmin();
        $this->request = Request::instance();
    }

    function IsAdmin()
    {
        $session_id = trim(session('admin.id'));
        if(!empty($session_id))
        {
            //权限管理
        }
        else
        {
            $this->redirect('admin/login/index');
        }
    }
}