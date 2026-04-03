<?php
declare(strict_types=1);

namespace plugin\collect\adapter\bili;

use plugin\collect\service\Config;
use plugin\collect\contract\BaseAdapter;

/**
 * B站 - App 渠道采集适配器
 * @class BiliApp
 * @package plugin\collect\adapter\bili
 */
class BiliApp extends BaseAdapter
{
    protected string $platform = 'bili';
    protected string $channel = 'app';
    protected string $baseUrl = 'https://app.bilibili.com';

    public function getCode(): string { return $this->platform . '_' . $this->channel; }
    public function getName(): string { return 'B站 App'; }
    public function getPlatform(): string { return $this->platform; }
    public function getChannel(): string { return $this->channel; }

    protected function initDefaultHeaders(): void
    {
        $this->defaultConfig = $this->defaultConfig->withHeaders([
            'User-Agent'   => 'Bilibili/7.27.0 (iPhone; iOS 17.2; Scale/3.0)',
            'Referer'      => 'https://bilibili.com/',
            'Accept'       => 'application/json',
            'Accept-Language' => 'zh-CN,zh;q=0.9',
        ]);
    }

    public function verify(Config|array|string|null $config = null): array
    {
        // TODO: B站 App 接口需要 buvid3 等参数
        return [false, [], 'TODO: 待实现'];
    }

    public function getUserInfo(string $uid, Config|array|string|null $config = null): array
    {
        // TODO: B站 App 用户信息接口
        return [false, [], 'TODO: 待实现'];
    }

    public function getFeeds(Config|array|string|null $config = null): array
    {
        // TODO: B站 App 视频列表
        return [false, [], 'TODO: 待实现'];
    }

    public function getContentInfo(string $contentId, Config|array|string|null $config = null): array
    {
        // TODO: B站 App 视频详情
        return [false, [], 'TODO: 待实现'];
    }
}
