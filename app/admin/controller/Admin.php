<?php
namespace app\admin\controller;

use think\util\AdminParent;
use think\Db;

class Admin extends AdminParent
{
    //后台首页
    public function index()
    {
        return $this->fetch('index');
    }
    //管理员列表
    public function adminList()
    {
        $list = Db::name('admin')->where('super','NEQ',1)->order('id desc')->paginate(20);
        $this->assign('list',$list);
        return $this->fetch('adminList');
    }
    //添加管理员
    public function addAdmin()
    {
        if($this->request->method() == 'POST')
        {
            $post = $this->request->param();
            if($post['user'] == '' || $post['pass'] == '' || $post['re_pass'] == '')
            {
                $this->error('账号密码不能为空');
            }
            if($post['pass'] !== $post['re_pass'])
            {
                $this->error('两次输入的密码不同,请重新输入');
            }
            $is_user = Db::name('admin')->where('user',$post['user'])->find();
            if(!empty($is_user))
            {
                $this->error('该用户已存在');
            }
            $data = ['user'=>$post['user'],'pass'=>substr(md5($post['pass']),6,20),'create_time'=>time()];
            $add = Db::name('admin')->insert($data);
            if(!empty($add))
            {
                $this->success('保存成功','Admin/adminList');
            }
            else
            {
                $this->error('保存失败');
            }
        }
        $this->assign('info',false);
        return $this->fetch('addAdmin');
    }
    //修改管理员信息
    public function editAdmin()
    {
        if($this->request->method() == 'GET')
        {
            $id = $this->request->param()['id'];
            $info = Db::name('admin')->where('id',$id)->find();
            $this->assign('info',$info);
        }
        if($this->request->method() == 'POST')
        {
            /*随意修改某一个字段*/
            $post = $this->request->param();
            if(!empty($post['pass']) || !empty($post['re_pass']))
            {
                if($post['re_pass'] == $post['pass'])
                {
                    $post['pass'] = substr(md5($post['pass']),6,20);
                }
                else
                {
                    $this->error('两次输入的密码不同');
                }
            }
            foreach($post as $k => $v)
            {
                if($v == '' || $k == 're_pass')
                {
                    unset($post[$k]);
                }
            }
            $edit = Db::name('admin')->where('id',$post['id'])->update($post);
            if(!empty($edit))
            {
                $this->success('修改成功','admin/Admin/adminList');
            }
            else
            {
                $this->error('修改失败');
            }
        }

        return $this->fetch('addAdmin');
    }
    //删除管理员
    public function delAdmin()
    {
        $id = $this->request->param()['id'];
        $del = Db::name('admin')->where('super','<>',1)->where('id','=',$id)->delete();
        if(!empty($del))
        {
            $this->success('删除成功');
        }
        else
        {
            $this->error('删除失败');
        }
    }
    //登录日志
    public function loginLog()
    {
        $join = [['zx_admin zx2','zx1.uid=zx2.id']];
        $list = Db::name('login_log')->alias('zx1')->join($join)->field('zx1.*,zx2.user')->order('log_id desc')->paginate(50);
        $this->assign('list',$list);
        return $this->fetch('loginLog');
    }
}