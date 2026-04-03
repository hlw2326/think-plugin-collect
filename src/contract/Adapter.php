<?php
declare(strict_types=1);

namespace plugin\collect\contract;

use plugin\collect\service\Config;

/**
 * 采集适配器接口
 * 所有平台渠道适配器必须实现此接口
 * @interface Adapter
 * @package plugin\collect\contract
 */
interface Adapter
{
    // ── 元信息 ──────────────────────────────────────────────────

    /**
     * 获取适配器标识（平台_渠道，如 dy_web / ks_h5）
     */
    public function getCode(): string;

    /**
     * 获取适配器名称
     */
    public function getName(): string;

    /**
     * 获取平台标识
     */
    public function getPlatform(): string;

    /**
     * 获取渠道标识
     */
    public function getChannel(): string;

    // ── 配置（链式） ────────────────────────────────────────────

    /**
     * 获取当前默认配置对象
     */
    public function config(): Config;

    /**
     * 设置默认配置（替换，返回自身方便链式）
     */
    public function setConfig(Config $config): static;

    /**
     * 以链式方式累积配置，返回新实例
     * 用法：$api->with($config)->withProxy([...])->getUserInfo('uid')
     */
    public function with(Config $config): static;

    /**
     * 以数组方式链式累积配置（内部自动构建 Config）
     * 用法：$api->with(['cookie' => '...', 'timeout' => 20])
     */
    public function withOptions(array $options): static;

    /**
     * 设置默认 Cookie
     */
    public function withCookie(string $cookie): static;

    /**
     * 累积请求头
     */
    public function withHeaders(array $headers): static;

    /**
     * 设置代理
     */
    public function withProxy(array $proxy): static;

    /**
     * 设置 HTTP 代理（快捷方法）
     */
    public function withHttpProxy(string $host, int $port, string $user = '', string $pwd = ''): static;

    /**
     * 设置 SOCKS5 代理（快捷方法）
     */
    public function withSocks5Proxy(string $host, int $port, string $user = '', string $pwd = ''): static;

    /**
     * 设置请求超时（秒）
     */
    public function withTimeout(int $seconds): static;

    // ── 数据采集方法 ────────────────────────────────────────────

    /**
     * 验证 Cookie 是否有效
     * @param Config|array|string|null $config 临时配置（可选，会与默认配置合并）
     * @return array ['success' => bool, 'data' => array, 'message' => string]
     */
    public function verify(Config|array|string|null $config = null): array;

    /**
     * 获取用户信息
     * @param string $uid 用户ID
     * @param Config|array|string|null $config 临时配置
     * @return array
     */
    public function getUserInfo(string $uid, Config|array|string|null $config = null): array;

    /**
     * 获取内容流/作品列表
     * @param Config|array|string|null $config 临时配置
     * @return array
     */
    public function getFeeds(Config|array|string|null $config = null): array;

    /**
     * 获取内容详情
     * @param string $contentId 内容ID（视频ID/笔记ID等）
     * @param Config|array|string|null $config 临时配置
     * @return array
     */
    public function getContentInfo(string $contentId, Config|array|string|null $config = null): array;

    // ── 底层 HTTP ──────────────────────────────────────────────

    /**
     * 执行 HTTP GET 请求
     * @param string $url
     * @param Config|array|string|null $config 临时配置
     * @return array ['success' => bool, 'data' => mixed, 'message' => string]
     */
    public function get(string $url, Config|array|string|null $config = null): array;

    /**
     * 执行 HTTP POST 请求（默认 JSON body）
     * @param string $url
     * @param Config|array|string|null $config 临时配置
     * @return array
     */
    public function post(string $url, Config|array|string|null $config = null): array;
}
