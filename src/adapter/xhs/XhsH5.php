<?php
declare(strict_types=1);

namespace plugin\collect\adapter\xhs;

use plugin\collect\service\Config;
use plugin\collect\contract\BaseAdapter;

/**
 * 小红书 - H5 渠道采集适配器
 * @class XhsH5
 * @package plugin\collect\adapter\xhs
 */
class XhsH5 extends BaseAdapter
{
    protected string $platform = 'xhs';
    protected string $channel = 'h5';
    protected string $baseUrl = 'https://www.xiaohongshu.com';

    public function getCode(): string { return $this->platform . '_' . $this->channel; }
    public function getName(): string { return '小红书 H5'; }
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
        // TODO: 小红书 H5 Cookie 验证
        return [false, [], 'TODO: 待实现'];
    }

    public function getUserInfo(string $uid, Config|array|string|null $config = null): array
    {
        // TODO: 小红书 H5 用户信息接口
        return [false, [], 'TODO: 待实现'];
    }

    public function getFeeds(Config|array|string|null $config = null): array
    {
        // TODO: 小红书 H5 Feed 流
        return [false, [], 'TODO: 待实现'];
    }

    public function getContentInfo(string $contentId, Config|array|string|null $config = null): array
    {
        // TODO: 小红书 H5 笔记详情
        return [false, [], 'TODO: 待实现'];
    }
}
