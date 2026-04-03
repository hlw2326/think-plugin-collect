<?php
declare(strict_types=1);

namespace plugin\collect\adapter\tk;

use plugin\collect\service\Config;
use plugin\collect\contract\Adapter;

/**
 * TikTok 采集适配器入口
 * 支持 Web、H5 两个渠道
 * @class Tk
 * @package plugin\collect\adapter\tk
 */
class Tk
{
    protected static string $name = 'TikTok';
    protected static string $platform = 'tk';
    protected static array $channels = [
        'web' => TkWeb::class,
        'h5'  => TkH5::class,
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
            throw new \Exception("TikTok不支持渠道：{$channel}，支持的渠道：" . implode('、', array_keys(self::$channels)));
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
