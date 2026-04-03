<?php
declare(strict_types=1);

namespace plugin\collect\contract;

use Curl\Curl;
use plugin\collect\service\Config;

/**
 * 采集适配器抽象基类
 * 基于 php-curl-class 封装，统一处理配置合并、代理、HTTPS 等
 * 子类只需实现平台特有的 getUserInfo / getFeeds / getContentInfo / verify
 * @class BaseAdapter
 * @package plugin\collect\contract
 */
abstract class BaseAdapter implements Adapter
{
    /** @var Config 默认配置 */
    protected Config $defaultConfig;

    public function __construct(?string $cookie = null, ?Config $config = null)
    {
        $this->defaultConfig = $config ?? Config::make();
        if ($cookie !== null) {
            $this->defaultConfig = $this->defaultConfig->withCookie($cookie);
        }
        $this->initDefaultHeaders();
    }

    // ── 元信息（子类必须实现） ──────────────────────────────────

    abstract public function getCode(): string;
    abstract public function getName(): string;
    abstract public function getPlatform(): string;
    abstract public function getChannel(): string;

    // ── 配置方法（链式，返回新实例） ────────────────────────────

    public function config(): Config
    {
        return $this->defaultConfig;
    }

    public function setConfig(Config $config): static
    {
        $this->defaultConfig = $config;
        return $this;
    }

    public function with(Config $config): static
    {
        $clone = $this->newInstance($this->defaultConfig->merge($config));
        return $clone;
    }

    public function withOptions(array $options): static
    {
        return $this->with(Config::parse($options));
    }

    public function withCookie(string $cookie): static
    {
        return $this->with(Config::make()->withCookie($cookie));
    }

    public function withHeaders(array $headers): static
    {
        return $this->with(Config::make()->withHeaders($headers));
    }

    public function withProxy(array $proxy): static
    {
        return $this->with(Config::make()->withProxy($proxy));
    }

    public function withHttpProxy(string $host, int $port, string $user = '', string $pwd = ''): static
    {
        return $this->with(Config::make()->withHttpProxy($host, $port, $user, $pwd));
    }

    public function withSocks5Proxy(string $host, int $port, string $user = '', string $pwd = ''): static
    {
        return $this->with(Config::make()->withSocks5Proxy($host, $port, $user, $pwd));
    }

    public function withTimeout(int $seconds): static
    {
        return $this->with(Config::make()->withTimeout($seconds));
    }

    /**
     * 创建当前实例的克隆（供链式用）
     * 子类覆盖时需保持此行为
     */
    protected function newInstance(Config $config): static
    {
        $class = static::class;
        return new $class(null, $config);
    }

    /**
     * 初始化默认请求头（子类可覆盖，追加平台特有的默认头）
     * 在构造函数末尾自动调用，追加而非覆盖
     */
    protected function initDefaultHeaders(): void
    {
        // 子类覆盖此方法，调用 $this->defaultConfig->withHeaders([...])
    }

    // ── 采集方法（子类可选覆盖） ────────────────────────────────

    public function verify(Config|array|string|null $config = null): array
    {
        return [false, [], 'TODO: 待实现'];
    }

    public function getUserInfo(string $uid, Config|array|string|null $config = null): array
    {
        return [false, [], 'TODO: 待实现'];
    }

    public function getFeeds(Config|array|string|null $config = null): array
    {
        return [false, [], 'TODO: 待实现'];
    }

    public function getContentInfo(string $contentId, Config|array|string|null $config = null): array
    {
        return [false, [], 'TODO: 待实现'];
    }

    // ── HTTP 请求 ──────────────────────────────────────────────

    /**
     * GET 请求
     * @param string $url
     * @param Config|array|string|null $config
     * @return array ['success' => bool, 'data' => mixed, 'message' => string]
     */
    public function get(string $url, Config|array|string|null $config = null): array
    {
        $cfg = $this->resolveConfig($config);
        $curl = $this->buildCurl($cfg);
        $curl->get($url, $cfg->query ?: []);
        return $this->parseResponse($curl);
    }

    /**
     * POST 请求
     * @param string $url
     * @param Config|array|string|null $config
     * @return array
     */
    public function post(string $url, Config|array|string|null $config = null): array
    {
        $cfg = $this->resolveConfig($config);
        $curl = $this->buildCurl($cfg);
        if (!empty($cfg->data)) {
            $curl->post($url, $cfg->data);
        } else {
            $curl->post($url);
        }
        return $this->parseResponse($curl);
    }

    // ── 受保护方法（子类可用） ─────────────────────────────────

    /**
     * 构建 Curl 实例并应用配置
     */
    protected function buildCurl(Config $config): Curl
    {
        $curl = new Curl();

        // 超时
        $curl->setTimeout($config->timeout);

        // 重定向
        $curl->setOpt(CURLOPT_FOLLOWLOCATION, $config->followLocation);
        if ($config->maxRedirects !== null) {
            $curl->setOpt(CURLOPT_MAXREDIRS, $config->maxRedirects);
        }

        // 代理
        if (!empty($config->proxy)) {
            $this->applyProxy($curl, $config->proxy);
        }

        // SSL
        if ($config->sslCert === null) {
            $curl->setOpt(CURLOPT_SSL_VERIFYPEER, false);
            $curl->setOpt(CURLOPT_SSL_VERIFYHOST, 0);
        } else {
            $curl->setOpt(CURLOPT_SSL_VERIFYPEER, true);
            $curl->setOpt(CURLOPT_SSL_VERIFYHOST, 2);
            if ($config->sslCert !== '') {
                $curl->setOpt(CURLOPT_CAINFO, $config->sslCert);
            }
            if ($config->sslKey !== '') {
                $curl->setOpt(CURLOPT_SSLKEY, $config->sslKey);
            }
        }

        // 请求头（Cookie 单独处理）
        $headers = $config->headers;
        if ($config->cookie !== null && $config->cookie !== '') {
            $headers['Cookie'] = $config->cookie;
        }
        if (!empty($headers)) {
            $formatted = [];
            foreach ($headers as $key => $value) {
                if (is_string($key)) {
                    $formatted[] = "{$key}: {$value}";
                } else {
                    $formatted[] = $value;
                }
            }
            $curl->setHeaders($formatted);
        }

        // 返回头信息
        if ($config->returnHeaders) {
            $curl->setOpt(CURLOPT_HEADER, true);
        }

        return $curl;
    }

    /**
     * 应用代理配置到 Curl 实例
     */
    protected function applyProxy(Curl $curl, array $proxy): void
    {
        $type = $proxy['type'] ?? 'http';
        $host = $proxy['host'] ?? '';
        $port = $proxy['port'] ?? 0;
        $user = $proxy['user'] ?? '';
        $pwd  = $proxy['pwd']  ?? '';

        $scheme = match ($type) {
            'socks5' => CURLPROXY_SOCKS5,
            'socks4' => CURLPROXY_SOCKS4,
            default  => CURLPROXY_HTTP,
        };

        $curl->setOpt(CURLOPT_PROXYTYPE, $scheme);
        $curl->setOpt(CURLOPT_PROXY, $host);
        $curl->setOpt(CURLOPT_PROXYPORT, $port);

        if ($user !== '' && $pwd !== '') {
            $curl->setOpt(CURLOPT_PROXYUSERPWD, "{$user}:{$pwd}");
        }
    }

    /**
     * 解析 Curl 响应
     */
    protected function parseResponse(Curl $curl): array
    {
        if ($curl->isError()) {
            return [
                'success' => false,
                'data'    => [],
                'message' => $curl->getErrorMessage(),
                'httpCode'=> $curl->getHttpErrorCode(),
            ];
        }

        $body = $curl->getRawResponse();
        $decoded = json_decode($body, true);

        return [
            'success' => true,
            'data'    => $decoded ?? $body,
            'message' => '',
            'httpCode'=> $curl->getHttpCode(),
        ];
    }

    /**
     * 合并配置：默认配置 + 临时传入的配置
     */
    protected function resolveConfig(Config|array|string|null $config): Config
    {
        if ($config === null) {
            return $this->defaultConfig;
        }
        if ($config instanceof Config) {
            return $this->defaultConfig->merge($config);
        }
        return $this->defaultConfig->merge(Config::parse($config));
    }
}
