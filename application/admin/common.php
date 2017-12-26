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
    if($info['super'] == 1)
    {
        $info['menu'] = Db::name('menu')->where('parent_id',0)->where('status',1)->order('sort')->select();
    }
    else
    {
        $auth = Db::name('auth')->where('auth_id',$info['auth_id'])->field('menu_auth_id')->find();
        if(!empty($auth))
        {
            $auth = explode(',',$auth['menu_auth_id']);
            for($i=0,$len=count($auth);$i<$len;$i++)
            {
                $parent_menu = Db::name('menu')->where('parent_id',0)->where('menu_id',$auth[$i])->where('status',1)->find();
                if(empty($parent_menu))
                {
                    unset($auth[$i]);
                }
            }
            $info['menu'] = Db::name('menu')->where('menu_id','IN',implode(',',$auth))->whereOr(function($query){
                $query->where('url_m','admin')->where('url_c','admin')->where('url_f','index');
            })->order('sort')->select();
        }
        else
        {
            $info['menu'][] = Db::name('menu')->where('url_m','admin')->where('url_c','admin')->where('url_f','index')->find();
        }
    }
    $class = ['sa-side-typography','sa-side-widget','sa-side-table','sa-side-form','sa-side-ui','sa-side-chart','sa-side-folder','sa-side-calendar','sa-side-page'];
    if(!empty($info['menu']))
    {
        for($i=0,$len=count($info['menu']);$i<$len;$i++)
        {
            $info['menu'][$i]['class'] = $class[$i];
            $info['menu'][$i]['sub_menu'] = Db::name('menu')->where('parent_id',$info['menu'][$i]['menu_id'])->where('status',1)->select();
        }
    }
    return $info;
}