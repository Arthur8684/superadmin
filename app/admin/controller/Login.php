<?php
/**
 * Created by PhpStorm.
 * User: rj158
 * Date: 2017/11/23
 * Time: 14:38
 * 登陆，注销
 */
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
        if($request->isAjax() && $request->isPost())
        {
            $data = $this->request->post();
            $validate = Loader::validate('Login');
            //判断验证码
            if (!$validate->scene('login')->check($data)) {
                return json(['status'=>-1, 'msg'=>$validate->getError()]);
            }
            $info = Db::name('admin')->where('user',$data['username'])->find();
            if(empty($info))
            {
                return json(['msg'=>'该用户不存在或已被禁用']);
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
                return json(['status'=>1]);
            }
            else
            {
                return json(['msg'=>'账号密码不匹配']);
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
