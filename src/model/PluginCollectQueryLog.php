<?php
declare(strict_types=1);

namespace plugin\collect\model;

use think\admin\Model;

/**
 * 采集查询日志模型
 * @property int $id 主键ID
 * @property int $cookie_id 关联CookieID
 * @property string $platform 平台标识（dy/ks/bili/xhs/sph/tk）
 * @property string $channel 渠道类型（web/h5/app）
 * @property string $action 查询动作（verify/user_info/feeds/content_info）
 * @property string $param 查询参数
 * @property int $http_code HTTP状态码
 * @property int $exec_time 执行耗时(毫秒)
 * @property int $result_code 结果码(0失败,1成功)
 * @property string $result_msg 错误信息
 * @property string $result_data 结果数据摘要
 * @property string $ip 请求来源IP
 * @property string $create_at 查询时间
 * @class PluginCollectQueryLog
 * @package plugin\collect\model
 */
class PluginCollectQueryLog extends Model
{
    /** @var string 表名 */
    protected $table = 'plugin_collect_query_log';

    /** @var bool 自动写入时间戳 */
    protected $autoWriteTimestamp = true;

    /** @var string 时间戳字段 */
    protected $createTime = 'create_at';
    protected $updateTime = 'update_at';

    /**
     * 获取查询动作枚举
     */
    public static function getActionTypes(): array
    {
        return [
            'verify'       => ['label' => 'Verify Cookie',  'class' => 'layui-bg-blue'],
            'user_info'    => ['label' => 'Get User Info',  'class' => 'layui-bg-green'],
            'feeds'        => ['label' => 'Get Feeds',       'class' => 'layui-bg-cyan'],
            'content_info' => ['label' => 'Get Content',     'class' => 'layui-bg-orange'],
        ];
    }

    /**
     * 获取结果码标签
     */
    public static function getResultLabels(): array
    {
        return [
            0 => ['label' => lang('失败'), 'class' => 'layui-bg-red'],
            1 => ['label' => lang('成功'), 'class' => 'layui-bg-green'],
        ];
    }

    /**
     * 记录一条查询日志
     * @param array $data
     * @return int|string
     */
    public static function record(array $data): int|string
    {
        $data['result_data'] = isset($data['result_data']) && is_array($data['result_data'])
            ? mb_substr(json_encode($data['result_data'], JSON_UNESCAPED_UNICODE), 0, 2000)
            : ($data['result_data'] ?? '');
        return self::mk()->insertGetId($data);
    }
}
