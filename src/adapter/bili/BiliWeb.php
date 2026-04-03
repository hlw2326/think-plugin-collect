<?php
declare(strict_types=1);

namespace plugin\collect\adapter\bili;

use plugin\collect\service\Config;
use plugin\collect\contract\BaseAdapter;

/**
 * B站 - Web 渠道采集适配器
 * @class BiliWeb
 * @package plugin\collect\adapter\bili
 */
class BiliWeb extends BaseAdapter
{
    protected string $platform = 'bili';
    protected string $channel = 'web';
    protected string $baseUrl = 'https://www.bilibili.com';

    public function getCode(): string { return $this->platform . '_' . $this->channel; }
    public function getName(): string { return 'B站 Web'; }
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
        // TODO: 可请求 https://api.bilibili.com/x/web-interface/nav 验证登录态
        return [false, [], 'TODO: 待实现'];
    }

    public function getUserInfo(string $uid, Config|array|string|null $config = null): array
    {
        // TODO: GET https://api.bilibili.com/x/web-interface/card
        return [false, [], 'TODO: 待实现'];
    }

    public function getFeeds(Config|array|string|null $config = null): array
    {
        // TODO: GET https://api.bilibili.com/x/space/arc/search
        return [false, [], 'TODO: 待实现'];
    }

    public function getContentInfo(string $contentId, Config|array|string|null $config = null): array
    {
        // TODO: GET https://api.bilibili.com/x/web-interface/view
        return [false, [], 'TODO: 待实现'];
    }
}
