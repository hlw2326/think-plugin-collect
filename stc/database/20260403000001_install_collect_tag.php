<?php

declare(strict_types=1);

use think\admin\extend\PhinxExtend;
use think\migration\Migrator;

@set_time_limit(0);
@ini_set('memory_limit', '-1');

class InstallCollectTag extends Migrator
{
    /**
     * 创建标签管理表
     */
    public function up(): void
    {
        $table = $this->table('plugin_collect_tag', [
            'engine' => 'InnoDB',
            'collation' => 'utf8mb4_general_ci',
            'comment' => '插件-标签管理',
        ]);

        PhinxExtend::upgrade($table, [
            ['name', 'string', ['limit' => 255, 'default' => '', 'null' => true, 'comment' => '标签分类名称']],
            ['value', 'text', ['default' => null, 'null' => true, 'comment' => '子标签（逗号分隔）']],
            ['sort', 'integer', ['limit' => 11, 'default' => 0, 'null' => true, 'comment' => '排序权重']],
            ['status', 'integer', ['limit' => 1, 'default' => 1, 'null' => true, 'comment' => '状态(0禁用,1启用)']],
            ['create_at', 'datetime', ['default' => null, 'null' => true, 'comment' => '创建时间']],
            ['update_at', 'datetime', ['default' => null, 'null' => true, 'comment' => '更新时间']],
        ], [
            'name',
            'status',
            'sort',
            'create_at',
        ]);
    }

    /**
     * 回滚时删除表
     */
    public function down(): void
    {
        $this->table('plugin_collect_tag')->drop();
    }
}
