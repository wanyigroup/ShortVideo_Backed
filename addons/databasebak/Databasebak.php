<?php

namespace addons\databasebak;

use app\common\library\Menu;
use think\Addons;

/**
 * 数据库插件
 */
class Databasebak extends Addons
{

    /**
     * 插件安装方法
     * @return bool
     */
    public function install()
    {
        $menu = [
            [
                'name'    => 'general/databasebak',
                'title'   => '数据库管理',
                'icon'    => 'fa fa-database',
                'remark'  => '可在线进行一些简单的数据库表优化或修复,查看表结构和数据。也可以进行SQL语句的操作',
                'sublist' => [
                    ['name' => 'general/databasebak/index', 'title' => '查看'],
                    ['name' => 'general/databasebak/query', 'title' => '查询'],
                    ['name' => 'general/databasebak/backup', 'title' => '备份'],
                    ['name' => 'general/databasebak/restore', 'title' => '恢复'],
                ]
            ]
        ];
        Menu::create($menu, 'general');
        return true;
    }

    /**
     * 插件卸载方法
     * @return bool
     */
    public function uninstall()
    {

        Menu::delete('general/databasebak');
        return true;
    }
    
    /**
     * 插件启用方法
     */
    public function enable()
    {
        Menu::enable('general/databasebak');
    }

    /**
     * 插件禁用方法
     */
    public function disable()
    {
        Menu::disable('general/databasebak');
    }

}
