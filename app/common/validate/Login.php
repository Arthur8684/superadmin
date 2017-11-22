<?php

namespace app\common\validate;

use think\Validate;

class Login extends Validate
{
    protected $rule = [
        'username|帐号'      => 'require',
        'password|密码'     => 'require',
        'captcha|验证码'     => 'require|captcha'
    ];
    protected $scene = [
        'login'    => ['username', 'password', 'captcha'],
    ];
}