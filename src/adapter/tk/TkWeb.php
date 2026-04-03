<?php
declare(strict_types=1);

namespace plugin\collect\adapter\tk;

use plugin\collect\service\Config;
use plugin\collect\contract\BaseAdapter;

/**
 * TikTok - Web 渠道采集适配器
 * @class TkWeb
 * @package plugin\collect\adapter\tk
 */
class TkWeb extends BaseAdapter
{
    protected string $platform = 'tk';
    protected string $channel = 'web';
    protected string $baseUrl = 'https://www.tiktok.com';

    public function getCode(): string { return $this->platform . '_' . $this->channel; }
    public function getName(): string { return 'TikTok Web'; }
    public function getPlatform(): string { return $this->platform; }
    public function getChannel(): string { return $this->channel; }

    protected function initDefaultHeaders(): void
    {
        $this->defaultConfig = $this->defaultConfig->withHeaders([
            'User-Agent'   => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36',
            'Referer'      => $this->baseUrl . '/',
            'Accept'       => 'application/json, text/plain, */*',
            'Accept-Language' => 'en-US,en;q=0.9',
        ]);
    }

    public function verify(Config|array|string|null $config = null): array
    {
        // TODO: TikTok Web Cookie 验证
        return [false, [], 'TODO: 待实现'];
    }

    public function getUserInfo(string $uid, Config|array|string|null $config = null): array
    {
        // TODO: GET https://www.tiktok.com/api/user/detail/
        return [false, [], 'TODO: 待实现'];
    }

    public function getFeeds(Config|array|string|null $config = null): array
    {
        // TODO: TikTok Web Feed 流
        return [false, [], 'TODO: 待实现'];
    }

    public function getContentInfo(string $contentId, Config|array|string|null $config = null): array
    {
        // TODO: TikTok 视频详情
        return [false, [], 'TODO: 待实现'];
    }
}
