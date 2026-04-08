# Cookie 采集 API

多平台 Cookie 采集管理插件，支持抖音、快手、B站、小红书、TikTok、视频号六大平台，通过平台 + 渠道组合调用对应采集适配器完成数据采集。

> **作者微信**：hlw2326

---

## 用户解析器注册

插件通过 `UserResolverService` 获取当前登录用户 ID，**具体实现由主应用注册**：

```php
// 在主应用初始化时注册（参考 app/mini/service/ 或插件文档）
use hlw2326\mp\shared\service\UserResolverService;

UserResolverService::register(function (string $appid): ?string {
    return \app\mini\model\MiniUser::getCurrentUserId($appid);
});
```

---

## API 接口

ThinkAdmin 插件根据控制器命名空间自动发现路由。

路由映射规则：`plugin\{code}\controller\{module}\{controller}@{action}`

本插件 `{code}` 为 `collect`，控制器在 `controller/api/v1/` 下：

```
plugin\collect\controller\api\v1\Collect::platforms → /collect/api/v1/collect/platforms
plugin\collect\controller\api\v1\Collect::cookies    → /collect/api/v1/collect/cookies
plugin\collect\controller\api\v1\Collect::query      → /collect/api/v1/collect/query
plugin\collect\controller\api\v1\Collect::my          → /collect/api/v1/collect/my
plugin\collect\controller\api\v1\Collect::stats       → /collect/api/v1/collect/stats
```

### 接口认证

| 参数 | 位置 | 说明 |
|---|---|---|
| `appid` | Header / GET / POST | 小程序标识，用于解析用户身份 |

### 接口列表

| 接口 | 方法 | 登录 | 说明 |
|---|---|---|---|
| `/collect/api/v1/collect/platforms` | GET | 否 | 获取支持的平台列表 |
| `/collect/api/v1/collect/cookies` | GET | 否 | 获取可用 Cookie 列表 |
| `/collect/api/v1/collect/query` | POST | 是 | 执行采集查询 |
| `/collect/api/v1/collect/my` | GET | 是 | 获取当前用户的查询记录 |
| `/collect/api/v1/collect/stats` | GET | 是 | 获取当前用户的查询统计 |

### 采集查询接口

```
POST /collect/api/v1/collect/query
```

**请求参数：**

| 参数 | 类型 | 必填 | 说明 |
|---|---|---|---|
| `platform` | string | 是 | 平台标识（dy/ks/bili/xhs/sph/tk） |
| `action` | string | 是 | 查询动作（verify/user_info/feeds/content_info） |
| `cookie_id` | int | 否 | Cookie 记录 ID（从 cookies 接口获取） |
| `uid` | string | 条��� | 用户ID（action=user_info 时必填） |
| `content_id` | string | 条件 | 内容ID（action=content_info 时必填） |

**返回示例：**

```json
{
  "code": 1,
  "info": "查询成功",
  "data": {
    "data": {...},
    "exec_time": 123
  }
}
```

---

## 技术特性

- **php-curl-class**：基于 `php-curl-class/php-curl-class ^9.8` 封装，所有 HTTP 请求统一经过 Curl 类
- **链式配置**：所有配置通过 `Config` 对象传递，支持 `withXxx()` 链式调用
- **不可变设计**：`Config` 和适配器实例通过 `withXxx()` 方法返回新实例，原始对象不变
- **零耦合**：采集方法与底层 HTTP 完全解耦，子类只需关注接口地址和响应解析

---

## 平台 × 渠道矩阵

| 平台 | 标识 | Web | H5 | App |
|---|---|---|---|---|
| 抖音 | `dy` | ✅ | ✅ | - |
| 快手 | `ks` | ✅ | ✅ | - |
| B站 | `bili` | ✅ | ✅ | ✅ |
| 小红书 | `xhs` | ✅ | ✅ | - |
| 视频号 | `sph` | ✅ | ✅ | - |
| TikTok | `tk` | ✅ | ✅ | - |

---

## 调用方式

### 方式一：快捷调用（推荐）

```php
use hlw2326\collect\adapter\dy\Dy;
use hlw2326\collect\adapter\ks\Ks;
use hlw2326\collect\adapter\bili\Bili;

// 直接传入 cookie 字符串
$api = Dy::web($cookie);

// 传入配置数组
$api = Dy::web(['cookie' => $cookie, 'timeout' => 20]);

// 传入 Config 对象
$api = Dy::web(\hlw2326\collect\Config::make()->withCookie($cookie));

// 调用采集方法
$res = $api->getUserInfo('sec_uid_xxx');
```

### 方式二：链式配置

```php
$api = Dy::web($cookie)
    ->withHttpProxy('127.0.0.1', 7890)          // HTTP 代理
    ->withHeaders(['X-Custom-Header' => 'value']) // 追加请求头
    ->withTimeout(30);                            // 超时 30 秒

$res = $api->getUserInfo('sec_uid_xxx');
```

### 方式三：方法内临时配置

```php
$api = Dy::web($cookie); // 默认配置

// 这次请求临时换代理、超时
$res = $api->getUserInfo('sec_uid_xxx', [
    'proxy'   => ['type' => 'socks5', 'host' => '127.0.0.1', 'port' => 1080],
    'timeout' => 10,
]);

// 传入 Config 对象
$res = $api->getFeeds(\hlw2326\collect\Config::make()
    ->withHttpProxy('192.168.1.1', 8080)
    ->withHeaders(['Accept-Language' => 'en-US']));
```

### 方式四：通过 CollectService 主类

```php
use hlw2326\collect\service\CollectService;

// 等同于 Dy::web($cookie)
$api = CollectService::make('dy', 'web', $cookie);
```

---

## Config 配置项

所有配置均可通过 `Config` 对象或数组传入，支持以下字段：

| 字段 | 类型 | 说明 |
|---|---|---|
| `cookie` | `string` | Cookie 字符串 |
| `headers` | `array` | 自定义 HTTP 请求头（`['Key' => 'Value']`） |
| `query` | `array` | GET 请求 Query 参数 |
| `data` | `array` | POST 请求 Body 参数 |
| `proxy` | `array` | 代理配置 |
| `timeout` | `int` | 请求超时秒数（默认 15） |
| `followLocation` | `bool` | 是否跟随重定向（默认 true） |
| `maxRedirects` | `int` | 最大重定向次数 |
| `returnHeaders` | `bool` | 是否返回响应头 |
| `sslCert` | `string` | SSL 证书路径（空=不校验） |
| `sslKey` | `string` | SSL 证书 Key 路径 |

### 代理配置格式

```php
// HTTP 代理
['type' => 'http', 'host' => '127.0.0.1', 'port' => 7890, 'user' => '', 'pwd' => '']

// SOCKS5 代理
['type' => 'socks5', 'host' => '127.0.0.1', 'port' => 1080, 'user' => '', 'pwd' => '']
```

### 快捷方法

```php
Config::make()                                      // 创建空配置
Config::make()->withCookie('xxx')                  // 设置 Cookie
Config::make()->withHttpProxy('host', 7890)        // HTTP 代理
Config::make()->withSocks5Proxy('host', 1080)      // SOCKS5 代理
Config::make()->withHeaders([...])                  // 追加请求头
Config::make()->withQuery(['page' => 1])           // GET 参数
Config::make()->withData(['name' => 'value'])       // POST 参数
Config::make()->withTimeout(30)                     // 超时 30s
Config::make()->withFollowLocation(false)           // 关闭重定向
Config::make()->withSslVerify(false)                // 关闭 SSL 校验
Config::parse(['cookie' => '...', 'timeout' => 20]) // 从数组构建
```

---

## Adapter 接口方法

所有渠道适配器统一实现以下接口：

| 方法 | 说明 | 参数 |
|---|---|---|
| `verify($config?)` | 验证 Cookie 是否有效 | `$config`: 临时配置 |
| `getUserInfo($uid, $config?)` | 获取用户信息 | `$uid`: 用户ID, `$config`: 临时配置 |
| `getFeeds($config?)` | 获取内容流/作品列表 | `$config`: 临时配置 |
| `getContentInfo($contentId, $config?)` | 获取内容详情 | `$contentId`: 内容ID, `$config`: 临时配置 |
| `get($url, $config?)` | 发送 GET 请求 | 返回 `['success', 'data', 'message', 'httpCode']` |
| `post($url, $config?)` | 发送 POST 请求 | 返回 `['success', 'data', 'message', 'httpCode']` |

---

## 目录结构

```
think-collect/
├── composer.json
├── README.md
└── src/
    ├── Service.php                    # 插件服务注册
    ├── contract/
    │   ├── Adapter.php               # 采集适配器接口
    │   ├── BaseAdapter.php           # 抽象基类（Curl 封装）
    │   └── UserResolver.php           # 用户解析器接口
    ├── controller/
    │   └── api/v1/
    │       ├── Base.php              # API 基础控制器
    │       └── Collect.php           # 采集 API 控制器
    ├── service/
    │   ├── CollectService.php        # 主采集管理器
    │   ├── Config.php                # HTTP 请求配置对象
    │   └── UserResolverService.php   # 用户解析器服务
    └── adapter/
        ├── dy/    (Dy.php, DyWeb.php, DyH5.php)
        ├── ks/    (Ks.php, KsWeb.php, KsH5.php)
        ├── bili/  (Bili.php, BiliWeb.php, BiliH5.php, BiliApp.php)
        ├── xhs/   (Xhs.php, XhsWeb.php, XhsH5.php)
        ├── sph/   (Sph.php, SphWeb.php, SphH5.php)
        └── tk/    (Tk.php, TkWeb.php, TkH5.php)
```

---

## 安装说明

```bash
composer install
composer dumpautoload
```

---

## 配置说明

进入 **Cookie采集 → 采集配置**，可设置全局采集参数。