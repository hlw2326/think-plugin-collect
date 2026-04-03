<?php
declare(strict_types=1);

namespace plugin\collect\model;

use think\admin\Model;

/**
 * Cookie管理模型
 * @property int $id 主键ID
 * @property string $platform 平台标识（dy/ks/bili/xhs/sph/tk）
 * @property string $channel 渠道类型（web/h5/app）
 * @property string $name Cookie名称
 * @property string $account 关联账号
 * @property string $cookie Cookie内容
 * @property int $status 状态(0禁用,1启用)
 * @property int $last_verify_time 最后验证时间戳
 * @property int $last_use_time 最后使用时间戳
 * @property int $error_count 连续失败次数
 * @property string $create_at 创建时间
 * @property string $update_at 更新时间
 * @class PluginCollectCookie
 * @package plugin\collect\model
 */
class PluginCollectCookie extends Model
{
    /** @var string 表名 */
    protected $table = 'plugin_collect_cookie';

    /** @var bool 自动写入时间戳 */
    protected $autoWriteTimestamp = true;

    /** @var string 时间戳字段 */
    protected $createTime = 'create_at';
    protected $updateTime = 'update_at';

    /**
     * 获取平台类型枚举
     */
    public static function getPlatformTypes(): array
    {
        return [
            'dy'   => ['label' => '抖音',  'class' => 'layui-bg-blue'],
            'ks'   => ['label' => '快手',  'class' => 'layui-bg-orange'],
            'bili' => ['label' => 'B站',   'class' => 'layui-bg-pink'],
            'xhs'  => ['label' => '小红书', 'class' => 'layui-bg-red'],
            'sph'  => ['label' => '视频号', 'class' => 'layui-bg-green'],
            'tk'   => ['label' => 'TikTok', 'class' => 'layui-bg-cyan'],
        ];
    }

    /**
     * 获取渠道类型枚举
     */
    public static function getChannelTypes(): array
    {
        return [
            'web' => ['label' => 'Web',  'class' => 'layui-bg-blue'],
            'h5'  => ['label' => 'H5',   'class' => 'layui-bg-green'],
            'app' => ['label' => 'App',  'class' => 'layui-bg-orange'],
        ];
    }

    /**
     * 获取平台标签
     */
    public static function getPlatformLabel(string $platform): string
    {
        return self::getPlatformTypes()[$platform]['label'] ?? $platform;
    }

    /**
     * 获取渠道标签
     */
    public static function getChannelLabel(string $channel): string
    {
        return self::getChannelTypes()[$channel]['label'] ?? $channel;
    }
}
