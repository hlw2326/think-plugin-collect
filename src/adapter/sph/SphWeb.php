<?php
declare(strict_types=1);

namespace plugin\collect\adapter\sph;

use plugin\collect\service\Config;
use plugin\collect\contract\BaseAdapter;

/**
 * 视频号 - Web 渠道采集适配器
 * @class SphWeb
 * @package plugin\collect\adapter\sph
 */
class SphWeb extends BaseAdapter
{
    protected string $platform = 'sph';
    protected string $channel = 'web';
    protected string $baseUrl = 'https://channels.weixin.qq.com';

    public function getCode(): string { return $this->platform . '_' . $this->channel; }
    public function getName(): string { return '视频号 Web'; }
    public function getPlatform(): string { return $this->platform; }
    public function getChannel(): string { return $this->channel; }

    protected function initDefaultHeaders(): void
    {
        $this->defaultConfig = $this->defaultConfig->withHeaders([
            'User-Agent'   => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36',
            'Referer'      => $this->baseUrl . '/',
            'Accept'       => 'application/json, text/plain, */*',
            'Accept-Language' => 'zh-CN,zh;q=0.9',
        ]);
    }

    public function verify(Config|array|string|null $config = null): array
    {
        // TODO: 视频号 Web Cookie 验证
        return [false, [], 'TODO: 待实现'];
    }

    public function getUserInfo(string $uid, Config|array|string|null $config = null): array
    {
        // TODO: 视频号 Web 用户信息接口
        return [false, [], 'TODO: 待实现'];
    }

    public function getFeeds(Config|array|string|null $config = null): array
    {
        // TODO: 视频号 Web Feed 流
        return [false, [], 'TODO: 待实现'];
    }

    public function getContentInfo(string $contentId, Config|array|string|null $config = null): array
    {
        // TODO: 视频号内容详情
        return [false, [], 'TODO: 待实现'];
    }
}
