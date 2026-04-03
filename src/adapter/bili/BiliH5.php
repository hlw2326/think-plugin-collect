<?php
declare(strict_types=1);

namespace plugin\collect\adapter\bili;

use plugin\collect\service\Config;
use plugin\collect\contract\BaseAdapter;

/**
 * B站 - H5 渠道采集适配器
 * @class BiliH5
 * @package plugin\collect\adapter\bili
 */
class BiliH5 extends BaseAdapter
{
    protected string $platform = 'bili';
    protected string $channel = 'h5';
    protected string $baseUrl = 'https://m.bilibili.com';

    public function getCode(): string { return $this->platform . '_' . $this->channel; }
    public function getName(): string { return 'B站 H5'; }
    public function getPlatform(): string { return $this->platform; }
    public function getChannel(): string { return $this->channel; }

    protected function initDefaultHeaders(): void
    {
        $this->defaultConfig = $this->defaultConfig->withHeaders([
            'User-Agent'   => 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.0 Mobile/15E148 Safari/604.1',
            'Referer'      => $this->baseUrl . '/',
            'Accept'       => 'application/json, text/plain, */*',
            'Accept-Language' => 'zh-CN,zh;q=0.9',
        ]);
    }

    public function verify(Config|array|string|null $config = null): array
    {
        // TODO: B站 H5 Cookie 验证
        return [false, [], 'TODO: 待实现'];
    }

    public function getUserInfo(string $uid, Config|array|string|null $config = null): array
    {
        // TODO: GET https://m.bilibili.com/profile/{uid}
        return [false, [], 'TODO: 待实现'];
    }

    public function getFeeds(Config|array|string|null $config = null): array
    {
        // TODO: B站 H5 Feed 流
        return [false, [], 'TODO: 待实现'];
    }

    public function getContentInfo(string $contentId, Config|array|string|null $config = null): array
    {
        // TODO: B站 H5 视频详情
        return [false, [], 'TODO: 待实现'];
    }
}
