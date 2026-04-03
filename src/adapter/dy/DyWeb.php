<?php
declare(strict_types=1);

namespace plugin\collect\adapter\dy;

use plugin\collect\service\Config;
use plugin\collect\contract\BaseAdapter;

/**
 * 抖音 - Web 渠道采集适配器
 * @class DyWeb
 * @package plugin\collect\adapter\dy
 */
class DyWeb extends BaseAdapter
{
    protected string $platform = 'dy';
    protected string $channel = 'web';

    protected string $baseUrl = 'https://www.douyin.com';

    public function getCode(): string
    {
        return $this->platform . '_' . $this->channel;
    }

    public function getName(): string
    {
        return '抖音 Web';
    }

    public function getPlatform(): string
    {
        return $this->platform;
    }

    public function getChannel(): string
    {
        return $this->channel;
    }

    protected function initDefaultHeaders(): void
    {
        $this->defaultConfig = $this->defaultConfig->withHeaders([
            'User-Agent'   => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36',
            'Referer'     => $this->baseUrl . '/',
            'Accept'      => 'application/json, text/plain, */*',
            'Accept-Language' => 'zh-CN,zh;q=0.9',
        ]);
    }

    public function verify(Config|array|string|null $config = null): array
    {
        // TODO: 请求一个需要登录态的接口，检查响应是否包含用户信息
        return [false, [], 'TODO: 待实现'];
    }

    public function getUserInfo(string $uid, Config|array|string|null $config = null): array
    {
        // TODO: 抖音 Web 用户信息接口
        // 接口示例：GET https://www.douyin.com/aweme/v1/web/user/profile/other/
        return [false, [], 'TODO: 待实现'];
    }

    public function getFeeds(Config|array|string|null $config = null): array
    {
        // TODO: 抖音 Web 作品列表接口
        // 接口示例：GET https://www.douyin.com/aweme/v1/web/aweme/post/
        return [false, [], 'TODO: 待实现'];
    }

    public function getContentInfo(string $contentId, Config|array|string|null $config = null): array
    {
        // TODO: 抖音 Web 视频详情接口
        // 接口示例：GET https://www.douyin.com/aweme/v1/web/aweme/detail/
        return [false, [], 'TODO: 待实现'];
    }
}
