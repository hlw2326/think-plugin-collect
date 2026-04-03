<?php
declare(strict_types=1);

namespace plugin\collect\adapter\xhs;

use plugin\collect\service\Config;
use plugin\collect\contract\Adapter;

/**
 * 小红书采集适配器入口
 * 支持 Web、H5 两个渠道
 * @class Xhs
 * @package plugin\collect\adapter\xhs
 */
class Xhs
{
    protected static string $name = '小红书';
    protected static string $platform = 'xhs';
    protected static array $channels = [
        'web' => XhsWeb::class,
        'h5'  => XhsH5::class,
    ];

    public static function meta(): array
    {
        return [
            'name'     => self::$platform,
            'label'    => self::$name,
            'channels' => array_keys(self::$channels),
        ];
    }

    public static function make(string $channel, string|Config|array|null $config = null): Adapter
    {
        $channel = strtolower(trim($channel));
        if (!isset(self::$channels[$channel])) {
            throw new \Exception("小红书不支持渠道：{$channel}，支持的渠道：" . implode('、', array_keys(self::$channels)));
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

    public static function web(string|Config|array|null $config = null): Adapter
    {
        return self::make('web', $config);
    }

    public static function h5(string|Config|array|null $config = null): Adapter
    {
        return self::make('h5', $config);
    }
}
