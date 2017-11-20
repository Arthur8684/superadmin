<?php
namespace app\admin\controller;

use think\Controller;
use think\Request;
use think\Db;
use think\Loader;

class Login extends Controller
{
    //登陆模板
    public function index()
    {
        if(session('admin.id'))
        {
            $this->redirect('admin/Admin/index');
        }
        return $this->fetch();
    }
    //登陆验证
    public function loginAjax()
    {
        $request = Request::instance();
        if($request->method() == 'POST')
        {
            $data = $this->request->post();
            $validate = Loader::validate('Login');
            if (!$validate->scene('login')->check($data)) {
                return ajax_return_adv_error($validate->getError());
            }
            $info = Db::name('admin')->where('user',$data['username'])->find();
            if(empty($info))
            {
                return ajax_return_adv_error('帐号不存在或已禁用！');
            }
            if($info['user'] == $data['username'] && $info['pass'] == substr(md5($data['password']),6,20)) {
                //保存登录信息
                $ip = $request->ip();
                //$ip = '115.192.33.156';
                $update['last_login_ip'] = $ip;
                $update['login_time'] = time();
                if ($info['login_count'] <= 0)
                {
                    $update['last_login_time'] = time();
                }
                else
                {
                    $update['last_login_time'] = $info['login_time'];
                }
                $update['login_count'] = ['exp','login_count+1'];
                Db::name('admin')->where('id',$info['id'])->update($update);
                //插入登陆日志
                $log['uid'] = $info['id'];
                $log['login_ip'] = $ip;
                $log['login_location'] = implode(" ", \Ip::find($ip));
                $log['login_browser'] = \Agent::getBroswer();       //浏览器
                $log['login_os'] = \Agent::getOs();         //操作系统
                $log['login_time'] = time();
                Db::name('login_log')->insert($log);
                session('admin.id',$info['id']);
                //return json(['status'=>1]);
                return ajax_return_adv_error('登陆成功');
            }
            else
            {
                //return json(['msg'=>'账号不存在或已禁止使用']);
                return ajax_return_adv_error('账号密码不匹配');
            }
        }
    }

    //退出登陆
    public function loginQuit()
    {
        session('admin.id',null);
        $this->redirect('admin/Login/index');
    }
}
