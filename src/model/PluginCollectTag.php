<?php
declare(strict_types=1);

namespace plugin\collect\model;

use think\admin\Model;

/**
 * 标签管理模型
 * @property int $id 主键ID
 * @property string $name 标签分类名称
 * @property string $value 子标签（逗号分隔）
 * @property int $sort 排序权重
 * @property int $status 状态(0禁用,1启用)
 * @property string $create_at 创建时间
 * @property string $update_at 更新时间
 * @class PluginCollectTag
 * @package plugin\collect\model
 */
class PluginCollectTag extends Model
{
    /** @var string 表名 */
    protected $table = 'plugin_collect_tag';

    /** @var bool 自动写入时间戳 */
    protected $autoWriteTimestamp = true;

    /** @var string 时间戳字段 */
    protected $createTime = 'create_at';
    protected $updateTime = 'update_at';
}
