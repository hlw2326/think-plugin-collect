<?php
declare(strict_types=1);

namespace plugin\collect\service;

use think\CurlFile;

/**
 * HTTP 请求配置对象（不可变）
 * 通过 withXxx() 方法生成新实例，实现链式配置累积
 * @class Config
 * @package plugin\collect\service
 */
class Config
{
    /** @var string|null Cookie 字符串 */
    public ?string $cookie = null;

    /** @var array<string,string> 自定义 HTTP 请求头 */
    public array $headers = [];

    /** @var array 请求 Query 参数（GET URL 参数） */
    public array $query = [];

    /** @var array 请求 Body 参数（POST Form/JSON 数据） */
    public array $data = [];

    /** @var array 代理配置 ['type' => 'http', 'host' => '127.0.0.1', 'port' => 7890, 'user' => '', 'pwd' => ''] */
    public array $proxy = [];

    /** @var int 请求超时秒数 */
    public int $timeout = 15;

    /** @var bool 是否跟随重定向 */
    public bool $followLocation = true;

    /** @var int|null 最大重定向次数 */
    public ?int $maxRedirects = null;

    /** @var bool 是否返回头信息 */
    public bool $returnHeaders = false;

    /** @var string|null SSL 证书路径（空=不校验） */
    public ?string $sslCert = null;

    /** @var string|null SSL 证书 Key 路径 */
    public ?string $sslKey = null;

    /**
     * 工厂方法：创建默认配置（cookie 为空）
     */
    public static function make(): self
    {
        return new self();
    }

    /**
     * 工厂方法：从 cookie 字符串创建配置
     */
    public static function makeWithCookie(string $cookie): self
    {
        $config = new self();
        $config->cookie = $cookie;
        return $config;
    }

    /**
     * 合并另一个 Config 对象（返回新实例，不修改原对象）
     */
    public function merge(self $other): self
    {
        $merged = clone $this;
        if ($other->cookie !== null) {
            $merged->cookie = $other->cookie;
        }
        $merged->headers = array_merge($merged->headers, $other->headers);
        $merged->query   = array_merge($merged->query,   $other->query);
        $merged->data    = array_merge($merged->data,    $other->data);
        if (!empty($other->proxy)) {
            $merged->proxy = $other->proxy;
        }
        $merged->timeout        = $other->timeout !== 15 ? $other->timeout : $merged->timeout;
        $merged->followLocation = $other->followLocation !== true ? $other->followLocation : $merged->followLocation;
        if ($other->maxRedirects !== null) {
            $merged->maxRedirects = $other->maxRedirects;
        }
        $merged->returnHeaders = $other->returnHeaders !== false ? $other->returnHeaders : $merged->returnHeaders;
        if ($other->sslCert !== null) {
            $merged->sslCert = $other->sslCert;
            $merged->sslKey  = $other->sslKey;
        }
        return $merged;
    }

    // ── 链式配置方法（均返回新实例） ───────────────────────────

    /**
     * 设置 Cookie
     */
    public function withCookie(string $cookie): self
    {
        return (clone $this)->_setCookie($cookie);
    }

    /**
     * 设置多个请求头
     * @param array<string,string>|string[] $headers ['Content-Type' => 'application/json'] 或 ['X-Token: xxx']
     */
    public function withHeaders(array $headers): self
    {
        $clone = clone $this;
        foreach ($headers as $k => $v) {
            if (is_string($k)) {
                $clone->headers[$k] = $v;
            } else {
                $clone->headers[] = $v;
            }
        }
        return $clone;
    }

    /**
     * 添加单个请求头
     */
    public function withHeader(string $key, string $value): self
    {
        return $this->withHeaders([$key => $value]);
    }

    /**
     * 设置 Query 参数（GET 请求 URL 参数）
     */
    public function withQuery(array $query): self
    {
        $clone = clone $this;
        $clone->query = array_merge($clone->query, $query);
        return $clone;
    }

    /**
     * 设置 Body 参数（POST 请求数据）
     */
    public function withData(array $data): self
    {
        $clone = clone $this;
        $clone->data = array_merge($clone->data, $data);
        return $clone;
    }

    /**
     * 设置 HTTP/SOCKS5 代理
     * @param array $proxy ['type' => 'http'|'socks5', 'host' => '', 'port' => 0, 'user' => '', 'pwd' => '']
     */
    public function withProxy(array $proxy): self
    {
        $clone = clone $this;
        $clone->proxy = $proxy;
        return $clone;
    }

    /**
     * 设置 HTTP 代理（快捷方法）
     */
    public function withHttpProxy(string $host, int $port, string $user = '', string $pwd = ''): self
    {
        return $this->withProxy([
            'type' => 'http',
            'host' => $host,
            'port' => $port,
            'user' => $user,
            'pwd'  => $pwd,
        ]);
    }

    /**
     * 设置 SOCKS5 代理（快捷方法）
     */
    public function withSocks5Proxy(string $host, int $port, string $user = '', string $pwd = ''): self
    {
        return $this->withProxy([
            'type' => 'socks5',
            'host' => $host,
            'port' => $port,
            'user' => $user,
            'pwd'  => $pwd,
        ]);
    }

    /**
     * 设置请求超时（秒）
     */
    public function withTimeout(int $seconds): self
    {
        $clone = clone $this;
        $clone->timeout = $seconds;
        return $clone;
    }

    /**
     * 设置是否跟随重定向
     */
    public function withFollowLocation(bool $follow): self
    {
        $clone = clone $this;
        $clone->followLocation = $follow;
        return $clone;
    }

    /**
     * 设置最大重定向次数
     */
    public function withMaxRedirects(int $max): self
    {
        $clone = clone $this;
        $clone->maxRedirects = $max;
        return $clone;
    }

    /**
     * 设置 SSL 证书（留空则不校验）
     */
    public function withSslCert(string $certFile, string $keyFile = ''): self
    {
        $clone = clone $this;
        $clone->sslCert = $certFile;
        $clone->sslKey  = $keyFile;
        return $clone;
    }

    /**
     * 关闭 SSL 校验（测试环境用）
     */
    public function withSslVerify(bool $verify = false): self
    {
        $clone = clone $this;
        if (!$verify) {
            $clone->sslCert = null;
            $clone->sslKey  = null;
        }
        return $clone;
    }

    // ── 内部方法 ───────────────────────────────────────────────

    protected function _setCookie(string $cookie): self
    {
        $this->cookie = $cookie;
        return $this;
    }

    /**
     * 从数组或 Config 对象构建
     */
    public static function parse(array|string|self $input): self
    {
        if ($input instanceof self) {
            return $input;
        }
        if (is_string($input)) {
            return self::makeWithCookie($input);
        }
        $config = new self();
        foreach ($input as $k => $v) {
            if (property_exists($config, $k)) {
                $config->{$k} = $v;
            }
        }
        return $config;
    }
}
