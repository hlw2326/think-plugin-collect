<?php
declare(strict_types=1);

namespace plugin\collect\adapter\dy;

use plugin\collect\service\Config;
use plugin\collect\contract\Adapter;
use plugin\collect\contract\BaseAdapter;

/**
 * 抖音采集适配器入口
 * 支持 Web、H5 两个渠道
 * @class Dy
 * @package plugin\collect\adapter\dy
 */
class Dy
{
    protected static string $name = '抖音';
    protected static string $platform = 'dy';
    protected static array $channels = [
        'web' => DyWeb::class,
        'h5'  => DyH5::class,
    ];

    /**
     * 获取适配器元信息
     */
    public static function meta(): array
    {
        return [
            'name'     => self::$platform,
            'label'    => self::$name,
            'channels' => array_keys(self::$channels),
        ];
    }

    /**
     * 创建指定渠道的适配器实例
     * @param string $channel 渠道类型
     * @param string|Config|array|null $config Cookie字符串 / Config对象 / 配置数组
     * @return Adapter
     * @throws \Exception
     */
    public static function make(string $channel, string|Config|array|null $config = null): Adapter
    {
        $channel = strtolower(trim($channel));
        if (!isset(self::$channels[$channel])) {
            throw new \Exception("抖音不支持渠道：{$channel}，支持的渠道：" . implode('、', array_keys(self::$channels)));
        }

        $cookie = null;
        $extraConfig = null;

        if ($config !== null) {
            if ($config instanceof Config) {
                $extraConfig = $config;
            } elseif (is_string($config)) {
                $cookie = $config;
            } elseif (is_array($config)) {
                $extraConfig = Config::parse($config);
            }
        }

        $class = self::$channels[$channel];
        return new $class($cookie, $extraConfig);
    }

    /**
     * 创建 Web 渠道实例（快捷方法）
     */
    public static function web(string|Config|array|null $config = null): Adapter
    {
        return self::make('web', $config);
    }

    /**
     * 创建 H5 渠道实例（快捷方法）
     */
    public static function h5(string|Config|array|null $config = null): Adapter
    {
        return self::make('h5', $config);
    }
}
