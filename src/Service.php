<?php
declare(strict_types=1);

namespace plugin\collect;

use think\admin\Plugin;

/**
 * 插件服务注册
 * @class Service
 * @package plugin\collect
 */
class Service extends Plugin
{
    /**
     * 定义插件名称
     * @var string
     */
    protected $appName = '采集接口';

    /**
     * 定义安装包名
     * @var string
     */
    protected $package = 'hlw2326/think-plugin-collect';

    /**
     * 注册模块菜单（菜单由 stc 迁移脚本写入 system_menu，此处用于插件中心显示）
     */
    public static function menu(): array
    {
        $code = app(static::class)->appCode;
        return [
            [
                'name' => '采集接口',
                'subs' => [
                [
                    'name' => '采集配置',
                    'icon' => 'layui-icon layui-icon-set',
                    'node' => "{$code}/config/index",
                ],
                [
                    'name' => 'Cookie管理',
                    'icon' => 'layui-icon layui-icon-survey',
                    'node' => "{$code}/cookie/index",
                ],
                [
                    'name' => '查询日志',
                    'icon' => 'layui-icon layui-icon-log',
                    'node' => "{$code}/query_log/index",
                ],
                ],
            ],
        ];
    }
}
