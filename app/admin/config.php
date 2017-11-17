<?php

return [
    'template'  =>  [
        'layout_on'     =>  true,
        'layout_name'   =>  'layout'
    ],
    // 默认跳转页面对应的模板文件
    'dispatch_success_tmpl'  => THINK_PATH . 'tpl' . DS . 'custom_dispatch_jump.tpl',
    'dispatch_error_tmpl'    => THINK_PATH . 'tpl' . DS . 'custom_dispatch_jump.tpl',
];