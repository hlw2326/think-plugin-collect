<?php
declare(strict_types=1);

namespace plugin\collect\service;

use plugin\collect\contract\Adapter;

/**
 * 多平台 Cookie 采集管理器
 * 通过平台 + 渠道组合获取对应适配器，调用其方法完成采集
 * @class CollectService
 * @package plugin\collect\service
 */
class CollectService
{
    /**
     * 平台标识 => 入口类（完全限定名）
     * @var array<string, class-string>
     */
    protected static array $platforms = [
        'dy'   => \plugin\collect\adapter\dy\Dy::class,
        'ks'   => \plugin\collect\adapter\ks\Ks::class,
        'bili' => \plugin\collect\adapter\bili\Bili::class,
        'xhs'  => \plugin\collect\adapter\xhs\Xhs::class,
        'sph'  => \plugin\collect\adapter\sph\Sph::class,
        'tk'   => \plugin\collect\adapter\tk\Tk::class,
    ];

    /**
     * 获取所有平台标识
     */
    public static function platforms(): array
    {
        return array_keys(self::$platforms);
    }

    /**
     * 获取指定平台 + 渠道的适配器实例
     * @param string $platform 平台标识（dy/ks/bili/xhs/sph/tk）
     * @param string $channel 渠道类型（web/h5/app）
     * @param string|Config|array|null $configOrCookie Cookie字符串 / Config对象 / 配置数组
     * @return Adapter
     * @throws \Exception
     */
    public static function make(string $platform, string $channel, string|Config|array|null $configOrCookie = null): Adapter
    {
        $platform = strtolower(trim($platform));
        $channel   = strtolower(trim($channel));

        if (!isset(self::$platforms[$platform])) {
            throw new \Exception("不支持的平台：{$platform}，支持的平台：" . implode('、', self::platforms()));
        }

        $class = self::$platforms[$platform];
        return $class::make($channel, $configOrCookie);
    }

    /**
     * 获取指定平台的元信息
     * @return array{name:string, label:string, channels:array}
     * @throws \Exception
     */
    public static function info(string $platform): array
    {
        $platform = strtolower(trim($platform));
        if (!isset(self::$platforms[$platform])) {
            throw new \Exception("不支持的平台：{$platform}");
        }
        return self::$platforms[$platform]::meta();
    }

    /**
     * 获取所有平台的元信息
     * @return array<string,array{name:string, label:string, channels:array}>
     */
    public static function allInfo(): array
    {
        $result = [];
        foreach (self::$platforms as $key => $class) {
            $result[$key] = $class::meta();
        }
        return $result;
    }
}
