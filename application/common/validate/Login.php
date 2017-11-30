<?php
/**
 * Created by PhpStorm.
 * User: rj158
 * Date: 2017/11/24
 * Time: 11:05
 * 登陆验证规则
 */
namespace app\common\validate;

use think\Validate;

class Login extends Validate
{
    protected $rule = [
        'username|帐号'      => 'require',
        'password|密码'     => 'require',
        'captcha|验证码'     => 'require|captcha'
    ];
}