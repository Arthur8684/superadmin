<?php
use think\Response;
use think\Db;
/**
 * @param $int
 * @return bool|string
 */
function get_sex($int)
{
    switch($int)
    {
        case 1:
            return '男';
        case 2:
            return '女';
        case 3:
            return '保密';
        default:
            return false;
    }
}


function admin_info()
{
    $id = session('admin.id');
    $info = Db::name('admin')->where('id',$id)->find();
    return $info;
}