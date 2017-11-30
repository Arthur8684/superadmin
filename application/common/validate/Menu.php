<?php
/**
 * Created by PhpStorm.
 * User: rj158
 * Date: 2017/11/24
 * Time: 11:05
 * 菜单验证规则
 */
namespace app\common\validate;
use think\Validate;

class Menu extends Validate{
    protected $rule = [
        'sort|序号'      => 'require|number',
        'menu_title|菜单名称'     => 'require',
        'url_m|模块名称'     => 'require|alphaDash',
        'url_c|控制器名称'     => 'require|alphaDash',
        'url_f|方法名称'     => 'require|alphaDash'
    ];

}