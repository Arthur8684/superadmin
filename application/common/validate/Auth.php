<?php
/**
 * Created by PhpStorm.
 * User: rj158
 * Date: 2017/11/28
 * Time: 14:42
 * 权限验证规则
 */

namespace app\common\validate;
use think\Validate;

class Auth extends Validate
{
    protected $rule = [
        'auth_title|权限组名称'      => 'require'
    ];
}